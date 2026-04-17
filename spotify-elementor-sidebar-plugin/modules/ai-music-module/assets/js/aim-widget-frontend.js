document.addEventListener('click', async function (e) {
  const button = e.target.closest('.aim-widget-submit');
  if (!button) return;

  const widget = button.closest('.aim-widget');
  if (!widget) return;

  const textarea = widget.querySelector('.aim-widget-prompt');
  const results = widget.querySelector('.aim-widget-results');
  const status = widget.querySelector('.aim-widget-status');
  const limit = parseInt(widget.getAttribute('data-limit') || '6', 10);

  const prompt = textarea ? textarea.value.trim() : '';
  if (!prompt) {
    status.textContent = 'Please enter a video prompt.';
    return;
  }

  button.disabled = true;
  status.textContent = 'Analyzing prompt and finding matching tracks...';
  results.innerHTML = '';

  try {
    const response = await fetch(AIMWidget.endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': AIMWidget.nonce
      },
      body: JSON.stringify({
        prompt: prompt,
        limit: limit
      })
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || 'Request failed');
    }

    const tracks = (data && data.tracks) ? data.tracks : [];
    if (!tracks.length) {
      status.textContent = 'No matching tracks found.';
      return;
    }

    status.textContent = 'Found ' + tracks.length + ' matching tracks.';

    const html = tracks.map(track => {
      const reasons = (track.match_reasons || []).map(reason => `<li>${escapeHtml(reason)}</li>`).join('');
      const moods = (track.moods || []).map(tag => `<span class="aim-tag">${escapeHtml(tag)}</span>`).join('');
      const scenes = (track.scene_tags || []).map(tag => `<span class="aim-tag">${escapeHtml(tag)}</span>`).join('');

      return `
        <div class="aim-track-card">
          ${track.image ? `<img class="aim-track-thumb" src="${track.image}" alt="${escapeHtml(track.title || '')}">` : ''}
          <div class="aim-track-body">
            <h4 class="aim-track-title"><a href="${track.permalink}" target="_blank" rel="noopener">${escapeHtml(track.title || '')}</a></h4>
            <div class="aim-track-meta">
              <span><strong>Score:</strong> ${track.score}</span>
              <span><strong>Energy:</strong> ${escapeHtml(track.energy_label || '-')}</span>
              <span><strong>Tempo:</strong> ${escapeHtml(track.tempo_label || '-')}</span>
            </div>
            ${track.summary ? `<p class="aim-track-summary">${escapeHtml(track.summary)}</p>` : ''}
            <div class="aim-track-tags">${moods}${scenes}</div>
            ${reasons ? `<ul class="aim-track-reasons">${reasons}</ul>` : ''}
            ${track.price_html ? `<div class="aim-track-price">${track.price_html}</div>` : ''}
          </div>
        </div>
      `;
    }).join('');

    results.innerHTML = html;

  } catch (err) {
    status.textContent = err.message || 'Something went wrong.';
  } finally {
    button.disabled = false;
  }
});

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}
