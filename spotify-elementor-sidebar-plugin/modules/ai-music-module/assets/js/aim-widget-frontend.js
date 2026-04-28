document.addEventListener('click', async function (e) {
  const submitButton = e.target.closest('.aim-widget-submit');
  if (submitButton) {
    const widget = submitButton.closest('.aim-widget');
    if (!widget) return;
    await runSearch(widget, 1);
    return;
  }

  const pageButton = e.target.closest('.aim-widget-page-btn');
  if (pageButton) {
    const widget = pageButton.closest('.aim-widget');
    if (!widget) return;

    const page = parseInt(pageButton.getAttribute('data-page') || '1', 10);
    await runSearch(widget, page);
  }
});

async function runSearch(widget, page) {
  const textarea   = widget.querySelector('.aim-widget-prompt');
  const results    = widget.querySelector('.aim-widget-results');
  const status     = widget.querySelector('.aim-widget-status');
  const pagination = widget.querySelector('.aim-widget-pagination');
  const submitBtn  = widget.querySelector('.aim-widget-submit');

  const perPage   = parseInt(widget.getAttribute('data-per-page') || '6', 10);
  const showPrice = widget.getAttribute('data-show-price') === '1';
  const prompt    = textarea ? textarea.value.trim() : '';

  if (!prompt) {
    status.textContent = 'Please enter a video prompt.';
    return;
  }

  submitBtn.disabled = true;
  status.textContent = 'Analyzing prompt and finding matching tracks...';
  results.innerHTML = '';
  pagination.innerHTML = '';

  try {
    const response = await fetch(AIMWidget.endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': AIMWidget.nonce
      },
      body: JSON.stringify({
        prompt: prompt,
        page: page,
        per_page: perPage
      })
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || 'Request failed');
    }

    const tracks = data?.tracks || [];
    const pager  = data?.pagination || null;

    if (!tracks.length) {
      status.textContent = 'No matching tracks found.';
      return;
    }

    status.textContent = `Found ${pager?.total_items || tracks.length} matching tracks. Showing page ${pager?.page || 1}.`;

    results.innerHTML = tracks.map(track => {
      const reasons = (track.match_reasons || []).map(reason => `<li>${escapeHtml(reason)}</li>`).join('');
      const moods = renderTags(track.moods || []);
      const scenes = renderTags(track.scene_tags || []);
      const instruments = renderTags(track.instruments || []);

      return `
        <div class="aim-track-card">
          ${track.image ? `<img class="aim-track-thumb" src="${track.image}" alt="${escapeHtml(track.title || '')}">` : ''}
          <div class="aim-track-body">
            <h4 class="aim-track-title">
              <a href="${track.permalink}" target="_blank" rel="noopener">${escapeHtml(track.title || '')}</a>
            </h4>

            <div class="aim-track-meta">
              <span><strong>Score:</strong> ${track.score}</span>
              <span><strong>Energy:</strong> ${escapeHtml(track.energy_label || '-')}</span>
              <span><strong>Tempo:</strong> ${escapeHtml(track.tempo_label || '-')}</span>
            </div>

            ${track.summary ? `<p class="aim-track-summary">${escapeHtml(track.summary)}</p>` : ''}
            ${track.preview_url ? `
              <button 
                type="button" 
                class="aim-play-btn" 
                data-audio="${escapeHtml(track.preview_url)}" 
                data-title="${escapeHtml(track.title || '')}" data-artist="Artist name">
              
                ▶ Play
              </button>
            ` : ''}
            ${moods ? `<div class="aim-track-section"><strong>Moods:</strong> ${moods}</div>` : ''}
            ${scenes ? `<div class="aim-track-section"><strong>Scenes:</strong> ${scenes}</div>` : ''}
            ${instruments ? `<div class="aim-track-section"><strong>Instruments:</strong> ${instruments}</div>` : ''}

            ${reasons ? `<ul class="aim-track-reasons">${reasons}</ul>` : ''}

            ${showPrice && track.price_html ? `<div class="aim-track-price">${track.price_html}</div>` : ''}
          </div>
        </div>
      `;
    }).join('');

    pagination.innerHTML = renderPagination(pager);

  } catch (err) {
    status.textContent = err.message || 'Something went wrong.';
  } finally {
    submitBtn.disabled = false;
  }
}

function renderTags(tags) {
  if (!tags.length) return '';
  return tags.map(tag => `<span class="aim-tag">${escapeHtml(tag)}</span>`).join('');
}

function renderPagination(pager) {
  if (!pager || !pager.total_pages || pager.total_pages <= 1) return '';

  let html = `<div class="aim-pagination-wrap">`;

  if (pager.has_prev) {
    html += `<button type="button" class="aim-widget-page-btn" data-page="${pager.page - 1}">Prev</button>`;
  }

  for (let i = 1; i <= pager.total_pages; i++) {
    const activeClass = i === pager.page ? ' is-active' : '';
    html += `<button type="button" class="aim-widget-page-btn${activeClass}" data-page="${i}">${i}</button>`;
  }

  if (pager.has_next) {
    html += `<button type="button" class="aim-widget-page-btn" data-page="${pager.page + 1}">Next</button>`;
  }

  html += `</div>`;
  return html;
}

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}
// document.addEventListener('click', function(e) {
//   const btn = e.target.closest('.aim-play-btn');
//   if (!btn) return;

//   const widget = btn.closest('.aim-widget');
//   let player = widget.querySelector('.aim-global-audio-player');

//   if (!player) {
//     player = document.createElement('audio');
//     player.className = 'aim-global-audio-player';
//     player.controls = true;
//     player.style.width = '100%';
//     player.style.margin = '16px 0';
//     widget.querySelector('.aim-widget-results').before(player);
//   }

//   player.src = btn.getAttribute('data-audio');
//   player.play().catch(() => {
//     player.controls = true;
//   });
// });
function aimPlayWithSonaarSticky(audioUrl, title, artist) {
  if (!window.IRON || !IRON.sonaar || !IRON.sonaar.player) {
    console.warn('Sonaar sticky player chưa sẵn sàng');
    return;
  }

  var player = IRON.sonaar.player;

  player.list = {
    playlist_name: title || '',
    type: 'audio',
    tracks: [{
      mp3: audioUrl,
      track_title: title || '',
      album_title: title || '',
      track_artist: artist || '',
      poster: '',
      sourcePostID: '',
      track_pos: 0,
      id: '',
      has_lyric: false,
      song_store_list: [],
      album_store_list: [],
      optional_storelist_cta: []
    }]
  };

  player.currentTrack = 0;
  player.classes.emptyPlayer = false;
  player.classes.enable = true;
  player.minimize = false;

  jQuery('#sonaar-player').show().addClass('enable');

  player.handleTrackChange();
  player.playAudio();
}

function forcePlayerStickyBottom() {
  const el = document.getElementById('sonaar-player');
  if (!el) return;

  el.style.display = 'block';
  el.style.bottom = '0px';
}

jQuery(document).on('click', '.aim-play-btn', function(e) {
  e.preventDefault();

  var $btn = jQuery(this);
  setTimeout(forcePlayerStickyBottom, 100);
  aimPlayWithSonaarSticky(
    $btn.data('audio'),
    $btn.data('title'),
    $btn.data('artist')
  );
});