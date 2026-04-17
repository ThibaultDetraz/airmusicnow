<?php
namespace YourPlugin\AI_Music;

if (!defined('ABSPATH')) exit;

class Audio_Analysis_Queue {

    const HOOK  = 'aim_run_audio_analysis';
    const GROUP = 'aim-audio';

    public static function init(): void {
        add_action(self::HOOK, [__CLASS__, 'run_worker'], 10, 1);
    }

    public static function enqueue_product(int $product_id, bool $force = false): void {
        if ($force && function_exists('as_unschedule_all_actions')) {
            as_unschedule_all_actions(self::HOOK, ['product_id' => $product_id], self::GROUP);
        }

        if (function_exists('as_has_scheduled_action')) {
            $existing = as_has_scheduled_action(self::HOOK, ['product_id' => $product_id], self::GROUP);
            if ($existing && !$force) {
                return;
            }
        }

        if (function_exists('as_enqueue_async_action')) {
            as_enqueue_async_action(self::HOOK, ['product_id' => $product_id], self::GROUP);
            return;
        }

        wp_schedule_single_event(time() + 5, self::HOOK, [['product_id' => $product_id]]);
    }

    public static function enqueue_retry(int $product_id, int $delay_minutes = 10): void {
        $timestamp = time() + ($delay_minutes * 60);

        if (function_exists('as_schedule_single_action')) {
            as_schedule_single_action($timestamp, self::HOOK, ['product_id' => $product_id], self::GROUP);
            return;
        }

        wp_schedule_single_event($timestamp, self::HOOK, [['product_id' => $product_id]]);
    }

    public static function run_worker($args): void {
        $product_id = is_array($args) ? (int) ($args['product_id'] ?? 0) : (int) $args;
        if (!$product_id) return;

        $worker = new Audio_Analysis_Worker();
        $worker->process($product_id);
    }
}
