<?php

namespace AstrologySuiteFreeAstroAPI;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private const RATE_LIMIT_MAX_REQUESTS = 30;
    private const RATE_LIMIT_WINDOW_SECONDS = 60;
    private const UPSTREAM_BASE_URL = 'https://api.freeastroapi.com/api/v1';
    private const UPSTREAM_SERVICE_URL = 'https://www.freeastroapi.com/';
    private const UPSTREAM_TERMS_URL = 'https://www.freeastroapi.com/terms';
    private const UPSTREAM_PRIVACY_URL = 'https://www.freeastroapi.com/privacy';
    private const UPSTREAM_SUPPORT_URL = 'https://www.freeastroapi.com/contact';
    private const CALCULATION_CAPABILITY = 'edit_posts';

    public const OPTION_API_KEY = 'openastro_api_key';
    public const OPTION_REPORT_DETAIL = 'openastro_report_detail';
    public const OPTION_REPORT_MAX_PLANETS = 'openastro_report_max_planets';
    public const OPTION_REPORT_MAX_ASPECTS = 'openastro_report_max_aspects';
    public const OPTION_REPORT_SHOW_TABLES = 'openastro_report_show_tables';
    public const OPTION_REPORT_SHOW_ANGLES = 'openastro_report_show_angles';
    public const OPTION_CHART_SHOW_PRINT_BUTTON = 'openastro_chart_show_print_button';
    public const OPTION_OBJ_ASC = 'openastro_obj_asc';
    public const OPTION_OBJ_MC = 'openastro_obj_mc';
    public const OPTION_OBJ_CHIRON = 'openastro_obj_chiron';
    public const OPTION_OBJ_LILITH = 'openastro_obj_lilith';
    public const OPTION_OBJ_TRUE_NODE = 'openastro_obj_true_node';
    public const OPTION_OBJ_MEAN_NODE = 'openastro_obj_mean_node';
    public const OPTION_OBJ_PART_OF_FORTUNE = 'openastro_obj_part_of_fortune';
    public const OPTION_STYLE_SHOW_BORDER = 'openastro_style_show_border';
    public const OPTION_STYLE_BG_COLOR = 'openastro_style_bg_color';
    public const OPTION_STYLE_TEXT_COLOR = 'openastro_style_text_color';
    public const OPTION_STYLE_INPUT_BORDER_RADIUS = 'openastro_style_input_border_radius';
    public const OPTION_STYLE_INPUT_BORDER_COLOR = 'openastro_style_input_border_color';
    public const OPTION_STYLE_INPUT_BG_COLOR = 'openastro_style_input_bg_color';
    public const OPTION_STYLE_INPUT_TEXT_COLOR = 'openastro_style_input_text_color';
    public const OPTION_STYLE_INPUT_PLACEHOLDER_COLOR = 'openastro_style_input_placeholder_color';
    public const OPTION_STYLE_BUTTON_BORDER_RADIUS = 'openastro_style_button_border_radius';
    public const OPTION_STYLE_BUTTON_BG_COLOR = 'openastro_style_button_bg_color';
    public const OPTION_STYLE_BUTTON_TEXT_COLOR = 'openastro_style_button_text_color';
    public const OPTION_STYLE_BUTTON_BORDER_COLOR = 'openastro_style_button_border_color';
    public const OPTION_STYLE_LABEL_COLOR = 'openastro_style_label_color';
    public const OPTION_STYLE_LABEL_WEIGHT = 'openastro_style_label_weight';
    public const OPTION_STYLE_FOCUS_RING_COLOR = 'openastro_style_focus_ring_color';
    public const OPTION_STYLE_ASTRO_FONT_SIZE = 'openastro_style_astro_font_size';
    public const OPTION_STYLE_ASTRO_FONT_COLOR = 'openastro_style_astro_font_color';
    public const OPTION_TYPO_H2 = 'openastro_typo_h2';
    public const OPTION_TYPO_H3 = 'openastro_typo_h3';
    public const OPTION_TYPO_H4 = 'openastro_typo_h4';
    public const OPTION_TYPO_BODY = 'openastro_typo_body';
    public const OPTION_TYPO_TABLE = 'openastro_typo_table';
    public const OPTION_TYPO_INPUT = 'openastro_typo_input';
    public const OPTION_TYPO_BUTTON = 'openastro_typo_button';
    public const OPTION_TYPO_META = 'openastro_typo_meta';

    public static function boot(): void
    {
        add_action('init', [__CLASS__, 'migrate_legacy_options'], 5);
        add_action('admin_init', [__CLASS__, 'register_settings']);
        add_action('admin_init', [__CLASS__, 'add_privacy_policy_content']);
        add_action('admin_menu', [__CLASS__, 'register_menu']);
        add_action('admin_notices', [__CLASS__, 'maybe_render_block_migration_notice']);
        add_action('admin_post_openastro_migrate_blocks', [__CLASS__, 'handle_block_migration']);
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
        add_action('init', [__CLASS__, 'register_blocks']);
        add_filter('plugin_action_links_' . plugin_basename(OPENASTRO_SUITE_FILE), [__CLASS__, 'add_plugin_action_links']);
    }

    public static function add_plugin_action_links(array $links): array
    {
        $settings_url = admin_url('admin.php?page=openastro-settings');
        $settings_link = '<a href="' . esc_url($settings_url) . '">' . esc_html__('Settings', 'astrology-suite-freeastroapi') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    private static function legacy_option_name(string $name): string
    {
        return str_replace('openastro_', 'freeastro_', $name);
    }

    private static function get_option_value(string $name, $default)
    {
        $sentinel = '__openastro_option_missing__';
        $value = get_option($name, $sentinel);
        if ($value !== $sentinel) {
            return $value;
        }
        return get_option(self::legacy_option_name($name), $default);
    }

    private static function current_user_can_calculate(): bool
    {
        return current_user_can(self::CALCULATION_CAPABILITY);
    }

    private static function frontend_access_message(): string
    {
        return __('This astrology form is available only to logged-in users who can edit posts on this site.', 'astrology-suite-freeastroapi');
    }

    public static function migrate_legacy_options(): void
    {
        if ((bool) get_option('openastro_migration_done', false)) {
            return;
        }

        $keys = [
            self::OPTION_API_KEY,
            self::OPTION_REPORT_DETAIL,
            self::OPTION_REPORT_MAX_PLANETS,
            self::OPTION_REPORT_MAX_ASPECTS,
            self::OPTION_REPORT_SHOW_TABLES,
            self::OPTION_REPORT_SHOW_ANGLES,
            self::OPTION_CHART_SHOW_PRINT_BUTTON,
            self::OPTION_OBJ_ASC,
            self::OPTION_OBJ_MC,
            self::OPTION_OBJ_CHIRON,
            self::OPTION_OBJ_LILITH,
            self::OPTION_OBJ_TRUE_NODE,
            self::OPTION_OBJ_MEAN_NODE,
            self::OPTION_OBJ_PART_OF_FORTUNE,
            self::OPTION_STYLE_SHOW_BORDER,
            self::OPTION_STYLE_BG_COLOR,
            self::OPTION_STYLE_TEXT_COLOR,
            self::OPTION_STYLE_INPUT_BORDER_RADIUS,
            self::OPTION_STYLE_INPUT_BORDER_COLOR,
            self::OPTION_STYLE_INPUT_BG_COLOR,
            self::OPTION_STYLE_INPUT_TEXT_COLOR,
            self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR,
            self::OPTION_STYLE_BUTTON_BORDER_RADIUS,
            self::OPTION_STYLE_BUTTON_BG_COLOR,
            self::OPTION_STYLE_BUTTON_TEXT_COLOR,
            self::OPTION_STYLE_BUTTON_BORDER_COLOR,
            self::OPTION_STYLE_LABEL_COLOR,
            self::OPTION_STYLE_LABEL_WEIGHT,
            self::OPTION_STYLE_FOCUS_RING_COLOR,
            self::OPTION_STYLE_ASTRO_FONT_SIZE,
            self::OPTION_STYLE_ASTRO_FONT_COLOR,
        ];

        foreach ($keys as $key) {
            $value = get_option($key, '__openastro_option_missing__');
            if ($value !== '__openastro_option_missing__') {
                continue;
            }
            $legacy = self::legacy_option_name($key);
            $legacyValue = get_option($legacy, '__openastro_option_missing__');
            if ($legacyValue !== '__openastro_option_missing__') {
                update_option($key, $legacyValue);
            }
        }

        update_option('openastro_migration_done', 1, false);
    }

    public static function sanitize_typography_size($value): int
    {
        return max(10, min(48, (int) $value));
    }

    public static function sanitize_radius_size($value): int
    {
        return max(0, min(40, (int) $value));
    }

    public static function sanitize_hex_color_value($value): string
    {
        $candidate = trim((string) $value);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $candidate) ? strtolower($candidate) : '#000000';
    }

    public static function sanitize_label_weight($value): string
    {
        $candidate = trim((string) $value);
        return in_array($candidate, ['normal', '500', '600', '700'], true) ? $candidate : 'normal';
    }

    public static function register_settings(): void
    {
        register_setting('openastro_settings_group', self::OPTION_API_KEY, [
            'type' => 'string',
            'sanitize_callback' => static fn($v) => trim(sanitize_text_field((string) $v)),
            'default' => '',
        ]);

        register_setting('openastro_settings_group', self::OPTION_REPORT_DETAIL, [
            'type' => 'string',
            'sanitize_callback' => static function ($value): string {
                $val = sanitize_key((string) $value);
                return in_array($val, ['full', 'concise'], true) ? $val : 'full';
            },
            'default' => 'full',
        ]);

        register_setting('openastro_settings_group', self::OPTION_REPORT_MAX_PLANETS, [
            'type' => 'integer',
            'sanitize_callback' => static fn($v): int => max(1, min(20, (int) $v)),
            'default' => 13,
        ]);

        register_setting('openastro_settings_group', self::OPTION_REPORT_MAX_ASPECTS, [
            'type' => 'integer',
            'sanitize_callback' => static fn($v): int => max(1, min(100, (int) $v)),
            'default' => 40,
        ]);

        register_setting('openastro_settings_group', self::OPTION_REPORT_SHOW_TABLES, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting('openastro_settings_group', self::OPTION_REPORT_SHOW_ANGLES, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting('openastro_settings_group', self::OPTION_CHART_SHOW_PRINT_BUTTON, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting('openastro_settings_group', self::OPTION_OBJ_ASC, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_MC, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_CHIRON, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_LILITH, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_TRUE_NODE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_MEAN_NODE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_OBJ_PART_OF_FORTUNE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting('openastro_settings_group', self::OPTION_STYLE_SHOW_BORDER, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_BG_COLOR, [
            'type' => 'string',
            'sanitize_callback' => static function ($v): string {
                $value = trim((string) $v);
                return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : '#ffffff';
            },
            'default' => '#ffffff',
        ]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_TEXT_COLOR, [
            'type' => 'string',
            'sanitize_callback' => static function ($v): string {
                $value = trim((string) $v);
                return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : '#111111';
            },
            'default' => '#111111',
        ]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_INPUT_BORDER_RADIUS, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_radius_size'], 'default' => 0]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_INPUT_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#000000']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_INPUT_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_INPUT_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#777777']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_BUTTON_BORDER_RADIUS, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_radius_size'], 'default' => 0]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_BUTTON_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_BUTTON_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#000000']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_BUTTON_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#000000']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_LABEL_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_LABEL_WEIGHT, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_label_weight'], 'default' => 'normal']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_FOCUS_RING_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#222222']);
        register_setting('openastro_settings_group', self::OPTION_STYLE_ASTRO_FONT_SIZE, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 20]);
        register_setting('openastro_settings_group', self::OPTION_STYLE_ASTRO_FONT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);

        register_setting('openastro_settings_group', self::OPTION_TYPO_H2, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 28]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_H3, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 22]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_H4, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 18]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_BODY, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 16]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_TABLE, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 14]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_INPUT, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 18]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_BUTTON, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 20]);
        register_setting('openastro_settings_group', self::OPTION_TYPO_META, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 13]);

        add_settings_section('openastro_settings_main', __('Astrology Suite', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_report_settings', __('Report Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_objects_settings', __('Objects & Points', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_style_settings', __('Style Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_typography_settings', __('Typography Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');

        add_settings_field(
            self::OPTION_API_KEY,
            __('API key', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_api_key_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_REPORT_DETAIL,
            __('Report detail level', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_detail_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_MAX_PLANETS,
            __('Max planets in report', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_max_planets_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_MAX_ASPECTS,
            __('Max aspects in report', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_max_aspects_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_SHOW_ANGLES,
            __('Show ASC/MC sections', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_angles_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_CHART_SHOW_PRINT_BUTTON,
            __('Show chart print button', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_chart_show_print_button_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_SHOW_TABLES,
            __('Show tables', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_tables_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(self::OPTION_OBJ_ASC, __('Include ASC', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_asc_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_MC, __('Include MC', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_mc_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_CHIRON, __('Include Chiron', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_chiron_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_LILITH, __('Include Lilith', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_lilith_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_TRUE_NODE, __('Include True Node', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_true_node_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_MEAN_NODE, __('Include Mean Node', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_mean_node_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_PART_OF_FORTUNE, __('Include Part of Fortune', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_part_of_fortune_field'], 'openastro-settings', 'openastro_objects_settings');

        add_settings_field(self::OPTION_STYLE_SHOW_BORDER, __('Show container border', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_show_border_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BG_COLOR, __('Plugin background color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_TEXT_COLOR, __('Plugin text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_input', __('Input', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_input'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_INPUT_BORDER_RADIUS, __('Input border radius (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_input_border_radius_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_INPUT_BORDER_COLOR, __('Input border color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_input_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_INPUT_BG_COLOR, __('Input background color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_input_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_INPUT_TEXT_COLOR, __('Input text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_input_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, __('Input placeholder color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_input_placeholder_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_button', __('Button', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_button'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, __('Button border radius (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_button_border_radius_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BUTTON_BG_COLOR, __('Button background color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_button_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BUTTON_TEXT_COLOR, __('Button text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_button_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BUTTON_BORDER_COLOR, __('Button border color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_button_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_labels', __('Labels & Focus', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_labels'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_LABEL_COLOR, __('Label color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_label_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_LABEL_WEIGHT, __('Label weight', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_label_weight_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_FOCUS_RING_COLOR, __('Focus ring color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_focus_ring_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_ASTRO_FONT_SIZE, __('Glyph font size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_astro_font_size_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_ASTRO_FONT_COLOR, __('Glyph font color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_astro_font_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_TYPO_H2, __('Title H2 size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_h2_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_H3, __('Section H3 size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_h3_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_H4, __('Subsection H4 size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_h4_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_BODY, __('Paragraph/body size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_body_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_TABLE, __('Table text size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_table_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_INPUT, __('Input text size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_input_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_BUTTON, __('Button text size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_button_field'], 'openastro-settings', 'openastro_typography_settings');
        add_settings_field(self::OPTION_TYPO_META, __('Helper/meta text size (px)', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_typography_meta_field'], 'openastro-settings', 'openastro_typography_settings');
    }

    public static function register_menu(): void
    {
        add_options_page(
            __('Astrology Suite', 'astrology-suite-freeastroapi'),
            __('Astrology Suite', 'astrology-suite-freeastroapi'),
            'manage_options',
            'openastro-settings',
            [__CLASS__, 'render_settings_page']
        );
    }

    public static function render_api_key_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_API_KEY, '');
        echo '<input type="password" class="regular-text" name="' . esc_attr(self::OPTION_API_KEY) . '" value="' . esc_attr($value) . '" autocomplete="off" />';
        echo '<p class="description">' . esc_html__('Get a free API key from freeastroapi.com.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('The plugin sends widget requests to FreeAstroAPI. See the Help tab for external-service details, privacy links, and access limits.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function add_privacy_policy_content(): void
    {
        if (!function_exists('wp_add_privacy_policy_content')) {
            return;
        }

        $policy = '<p>' . esc_html__('Astrology Suite for FreeAstroAPI connects to FreeAstroAPI to autocomplete cities and generate astrology calculations.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('When someone types into the city field, the plugin sends the typed city query and optional country filter to FreeAstroAPI over HTTPS so matching cities and timezones can be returned.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('When an authenticated user with permission to edit posts submits a natal chart, report, or simple-sign form, the plugin sends the submitted birth data and calculation options to FreeAstroAPI over HTTPS. This may include the optional name field, birth date, birth time, city name, latitude, longitude, timezone, interpretation settings, and the requested calculation mode. The site’s configured FreeAstroAPI key is also sent in the server-to-server request header for these protected calculation requests.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” feature stores the most recent successful requests in the visitor’s own browser local storage only.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . sprintf(
            wp_kses(
                /* translators: 1: FreeAstroAPI terms URL, 2: FreeAstroAPI privacy URL. */
                __('FreeAstroAPI terms of service: <a href="%1$s">Terms of Service</a>. FreeAstroAPI privacy policy: <a href="%2$s">Privacy Policy</a>.', 'astrology-suite-freeastroapi'),
                ['a' => ['href' => []]]
            ),
            esc_url(self::UPSTREAM_TERMS_URL),
            esc_url(self::UPSTREAM_PRIVACY_URL)
        ) . '</p>';

        wp_add_privacy_policy_content(
            __('Astrology Suite for FreeAstroAPI', 'astrology-suite-freeastroapi'),
            wp_kses_post($policy)
        );
    }

    public static function render_report_detail_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_REPORT_DETAIL, 'full');
        ?>
        <select name="<?php echo esc_attr(self::OPTION_REPORT_DETAIL); ?>">
            <option value="full" <?php selected($value, 'full'); ?>><?php esc_html_e('Full', 'astrology-suite-freeastroapi'); ?></option>
            <option value="concise" <?php selected($value, 'concise'); ?>><?php esc_html_e('Concise', 'astrology-suite-freeastroapi'); ?></option>
        </select>
        <?php
    }

    public static function render_report_max_planets_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_REPORT_MAX_PLANETS, 13);
        echo '<input type="number" min="1" max="20" name="' . esc_attr(self::OPTION_REPORT_MAX_PLANETS) . '" value="' . esc_attr((string) $value) . '" />';
    }

    public static function render_report_max_aspects_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_REPORT_MAX_ASPECTS, 40);
        echo '<input type="number" min="1" max="100" name="' . esc_attr(self::OPTION_REPORT_MAX_ASPECTS) . '" value="' . esc_attr((string) $value) . '" />';
    }

    public static function render_report_show_angles_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_ANGLES, true);
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_ANGLES) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Include Ascendant and Midheaven sections.', 'astrology-suite-freeastroapi') . '</label>';
    }

    public static function render_report_show_tables_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_TABLES, true);
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_TABLES) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Include planets/aspects tables.', 'astrology-suite-freeastroapi') . '</label>';
    }

    public static function render_chart_show_print_button_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_CHART_SHOW_PRINT_BUTTON, true);
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_CHART_SHOW_PRINT_BUTTON) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Show small print button at top-right of natal wheel.', 'astrology-suite-freeastroapi') . '</label>';
    }

    private static function render_checkbox_field(string $option, string $label, bool $default = true): void
    {
        $value = (bool) self::get_option_value($option, $default);
        echo '<input type="hidden" name="' . esc_attr($option) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr($option) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html($label) . '</label>';
    }

    public static function render_obj_asc_field(): void { self::render_checkbox_field(self::OPTION_OBJ_ASC, __('Enable Ascendant (ASC).', 'astrology-suite-freeastroapi')); }
    public static function render_obj_mc_field(): void { self::render_checkbox_field(self::OPTION_OBJ_MC, __('Enable Midheaven (MC).', 'astrology-suite-freeastroapi')); }
    public static function render_obj_chiron_field(): void { self::render_checkbox_field(self::OPTION_OBJ_CHIRON, __('Include Chiron in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_lilith_field(): void { self::render_checkbox_field(self::OPTION_OBJ_LILITH, __('Include Lilith in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_true_node_field(): void { self::render_checkbox_field(self::OPTION_OBJ_TRUE_NODE, __('Include True Node in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_mean_node_field(): void { self::render_checkbox_field(self::OPTION_OBJ_MEAN_NODE, __('Include Mean Node in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_part_of_fortune_field(): void { self::render_checkbox_field(self::OPTION_OBJ_PART_OF_FORTUNE, __('Include Part of Fortune in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }

    public static function render_style_show_border_field(): void { self::render_checkbox_field(self::OPTION_STYLE_SHOW_BORDER, __('Show border around plugin container.', 'astrology-suite-freeastroapi')); }

    public static function render_style_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BG_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_TEXT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_group_input(): void
    {
        echo '<p><strong>' . esc_html__('Input controls', 'astrology-suite-freeastroapi') . '</strong></p>';
    }

    public static function render_style_group_button(): void
    {
        echo '<p><strong>' . esc_html__('Button controls', 'astrology-suite-freeastroapi') . '</strong></p>';
    }

    public static function render_style_group_labels(): void
    {
        echo '<p><strong>' . esc_html__('Labels and focus states', 'astrology-suite-freeastroapi') . '</strong></p>';
    }

    public static function render_style_input_border_radius_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_RADIUS, 0);
        echo '<input type="number" min="0" max="40" step="1" name="' . esc_attr(self::OPTION_STYLE_INPUT_BORDER_RADIUS) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_style_input_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_COLOR, '#000000');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_INPUT_BORDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_input_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_BG_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_INPUT_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_input_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_TEXT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_INPUT_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_input_placeholder_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, '#777777');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_border_radius_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, 0);
        echo '<input type="number" min="0" max="40" step="1" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BORDER_RADIUS) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_style_button_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BG_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_TEXT_COLOR, '#000000');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_COLOR, '#000000');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BORDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_label_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_LABEL_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_LABEL_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_label_weight_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_LABEL_WEIGHT, 'normal');
        ?>
        <select name="<?php echo esc_attr(self::OPTION_STYLE_LABEL_WEIGHT); ?>">
            <option value="normal" <?php selected($value, 'normal'); ?>><?php esc_html_e('Normal', 'astrology-suite-freeastroapi'); ?></option>
            <option value="500" <?php selected($value, '500'); ?>>500</option>
            <option value="600" <?php selected($value, '600'); ?>>600</option>
            <option value="700" <?php selected($value, '700'); ?>>700</option>
        </select>
        <?php
    }

    public static function render_style_focus_ring_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_FOCUS_RING_COLOR, '#222222');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_FOCUS_RING_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_astro_font_size_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_SIZE, 20);
        echo '<input type="number" min="10" max="48" step="1" name="' . esc_attr(self::OPTION_STYLE_ASTRO_FONT_SIZE) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_style_astro_font_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_ASTRO_FONT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    private static function render_font_size_field(string $option, int $default): void
    {
        $value = (int) self::get_option_value($option, $default);
        echo '<input type="number" min="10" max="48" step="1" name="' . esc_attr($option) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_typography_h2_field(): void { self::render_font_size_field(self::OPTION_TYPO_H2, 28); }
    public static function render_typography_h3_field(): void { self::render_font_size_field(self::OPTION_TYPO_H3, 22); }
    public static function render_typography_h4_field(): void { self::render_font_size_field(self::OPTION_TYPO_H4, 18); }
    public static function render_typography_body_field(): void { self::render_font_size_field(self::OPTION_TYPO_BODY, 16); }
    public static function render_typography_table_field(): void { self::render_font_size_field(self::OPTION_TYPO_TABLE, 14); }
    public static function render_typography_input_field(): void { self::render_font_size_field(self::OPTION_TYPO_INPUT, 18); }
    public static function render_typography_button_field(): void { self::render_font_size_field(self::OPTION_TYPO_BUTTON, 20); }
    public static function render_typography_meta_field(): void { self::render_font_size_field(self::OPTION_TYPO_META, 13); }

    private static function settings_tabs(): array
    {
        return [
            'api' => __('API', 'astrology-suite-freeastroapi'),
            'report' => __('Report', 'astrology-suite-freeastroapi'),
            'objects' => __('Objects', 'astrology-suite-freeastroapi'),
            'style' => __('Style', 'astrology-suite-freeastroapi'),
            'typography' => __('Typography', 'astrology-suite-freeastroapi'),
            'help' => __('Help', 'astrology-suite-freeastroapi'),
        ];
    }

    private static function active_settings_tab(): string
    {
        $tab_param = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tab = is_string($tab_param) ? sanitize_key(wp_unslash($tab_param)) : 'api';
        $tabs = self::settings_tabs();
        return isset($tabs[$tab]) ? $tab : 'api';
    }

    private static function render_section_fields(string $section_id): void
    {
        global $wp_settings_fields;

        if (empty($wp_settings_fields['openastro-settings'][$section_id])) {
            return;
        }

        echo '<table class="form-table" role="presentation">';
        do_settings_fields('openastro-settings', $section_id);
        echo '</table>';
    }

    private static function render_tabbed_settings_fields(string $tab): void
    {
        if ($tab === 'api') {
            self::render_section_fields('openastro_settings_main');
            return;
        }
        if ($tab === 'report') {
            self::render_section_fields('openastro_report_settings');
            return;
        }
        if ($tab === 'objects') {
            self::render_section_fields('openastro_objects_settings');
            return;
        }
        if ($tab === 'style') {
            self::render_section_fields('openastro_style_settings');
            return;
        }
        if ($tab === 'typography') {
            self::render_section_fields('openastro_typography_settings');
        }
    }

    private static function all_option_keys(): array
    {
        return [
            self::OPTION_API_KEY,
            self::OPTION_REPORT_DETAIL,
            self::OPTION_REPORT_MAX_PLANETS,
            self::OPTION_REPORT_MAX_ASPECTS,
            self::OPTION_REPORT_SHOW_TABLES,
            self::OPTION_REPORT_SHOW_ANGLES,
            self::OPTION_CHART_SHOW_PRINT_BUTTON,
            self::OPTION_OBJ_ASC,
            self::OPTION_OBJ_MC,
            self::OPTION_OBJ_CHIRON,
            self::OPTION_OBJ_LILITH,
            self::OPTION_OBJ_TRUE_NODE,
            self::OPTION_OBJ_MEAN_NODE,
            self::OPTION_OBJ_PART_OF_FORTUNE,
            self::OPTION_STYLE_SHOW_BORDER,
            self::OPTION_STYLE_BG_COLOR,
            self::OPTION_STYLE_TEXT_COLOR,
            self::OPTION_STYLE_INPUT_BORDER_RADIUS,
            self::OPTION_STYLE_INPUT_BORDER_COLOR,
            self::OPTION_STYLE_INPUT_BG_COLOR,
            self::OPTION_STYLE_INPUT_TEXT_COLOR,
            self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR,
            self::OPTION_STYLE_BUTTON_BORDER_RADIUS,
            self::OPTION_STYLE_BUTTON_BG_COLOR,
            self::OPTION_STYLE_BUTTON_TEXT_COLOR,
            self::OPTION_STYLE_BUTTON_BORDER_COLOR,
            self::OPTION_STYLE_LABEL_COLOR,
            self::OPTION_STYLE_LABEL_WEIGHT,
            self::OPTION_STYLE_FOCUS_RING_COLOR,
            self::OPTION_STYLE_ASTRO_FONT_SIZE,
            self::OPTION_STYLE_ASTRO_FONT_COLOR,
            self::OPTION_TYPO_H2,
            self::OPTION_TYPO_H3,
            self::OPTION_TYPO_H4,
            self::OPTION_TYPO_BODY,
            self::OPTION_TYPO_TABLE,
            self::OPTION_TYPO_INPUT,
            self::OPTION_TYPO_BUTTON,
            self::OPTION_TYPO_META,
        ];
    }

    private static function render_preserve_hidden_fields(): void
    {
        foreach (self::all_option_keys() as $key) {
            $value = self::get_option_value($key, '');
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            } else {
                $value = (string) $value;
            }
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
        }
    }

    public static function render_settings_page(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $tabs = self::settings_tabs();
        $active_tab = self::active_settings_tab();
        $migrate_url = wp_nonce_url(
            admin_url('admin-post.php?action=openastro_migrate_blocks'),
            'openastro_migrate_blocks'
        );
        $css_doc_url = OPENASTRO_SUITE_URL . 'docs/CSS-CLASSES.md';
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Astrology Suite for FreeAstroAPI Settings', 'astrology-suite-freeastroapi'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <?php foreach ($tabs as $slug => $label) : ?>
                    <?php $tab_url = add_query_arg(['page' => 'openastro-settings', 'tab' => $slug], admin_url('admin.php')); ?>
                    <a href="<?php echo esc_url($tab_url); ?>" class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <?php if ($active_tab !== 'help') : ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('openastro_settings_group');
                    self::render_preserve_hidden_fields();
                    self::render_tabbed_settings_fields($active_tab);
                    submit_button();
                    ?>
                </form>
            <?php else : ?>
                <h2><?php esc_html_e('How To Add Blocks To A Page', 'astrology-suite-freeastroapi'); ?></h2>
                <ol>
                    <li><?php esc_html_e('Open a page in the WordPress block editor.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Search for "Astrology Suite" in the block inserter.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Choose a block and publish the page.', 'astrology-suite-freeastroapi'); ?></li>
                </ol>

                <h3><?php esc_html_e('Available Blocks', 'astrology-suite-freeastroapi'); ?></h3>
                <pre><code><?php echo esc_html("openastro/chart-only\nopenastro/report-only\nopenastro/chart-report\nopenastro/simple-sign-sun\nopenastro/simple-sign-moon\nopenastro/simple-sign-rising\nopenastro/simple-sign-midheaven"); ?></code></pre>

                <h3><?php esc_html_e('Code Block Example (Page Code Editor)', 'astrology-suite-freeastroapi'); ?></h3>
                <pre><code><?php echo esc_html("<!-- wp:openastro/chart-only /-->\n<!-- wp:openastro/report-only /-->\n<!-- wp:openastro/chart-report /-->\n<!-- wp:openastro/simple-sign-sun {\"includeInterpretation\":true,\"interpretationStyle\":\"improved\"} /-->\n<!-- wp:openastro/simple-sign-moon {\"includeInterpretation\":false,\"interpretationStyle\":\"improved\"} /-->"); ?></code></pre>

                <hr />
                <h2><?php esc_html_e('Legacy Block Migration', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('If your pages still use old freeastro/* blocks, run the migrator once to convert them to openastro/*.', 'astrology-suite-freeastroapi'); ?></p>
                <p><a class="button button-secondary" href="<?php echo esc_url($migrate_url); ?>"><?php esc_html_e('Run Block Migrator', 'astrology-suite-freeastroapi'); ?></a></p>

                <hr />
                <h2><?php esc_html_e('CSS Class Reference', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('Use these classes in your theme/customizer CSS to style Astrology Suite for FreeAstroAPI blocks.', 'astrology-suite-freeastroapi'); ?></p>
                <pre><code><?php echo esc_html(
"Container/Layout:
.wp-block-openastro-widget
.openastro-no-border
.openastro-output

Form/Input Controls:
.openastro-form-header
.openastro-history-label
.openastro-history-select
.openastro-form
.openastro-input
.openastro-inline
.openastro-btn
.openastro-status
.openastro-results
.openastro-result-btn

Report/Card Elements:
.openastro-report-title
.openastro-card
.openastro-access-note
.openastro-planet-item

Simple Sign Elements:
.openastro-simple-sign-card
.openastro-simple-sign-value
.openastro-simple-sign-interpretation

Chart/Print Elements:
.openastro-chart-card
.openastro-chart
.openastro-print-btn
.openastro-astro-glyph"
                ); ?></code></pre>
                <p><a href="<?php echo esc_url($css_doc_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Open full CSS class docs (Markdown)', 'astrology-suite-freeastroapi'); ?></a></p>

                <hr />
                <h2><?php esc_html_e('External Service Disclosure', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('Astrology Suite connects to FreeAstroAPI to search cities and generate astrology calculations.', 'astrology-suite-freeastroapi'); ?></p>
                <ul>
                    <li><?php esc_html_e('City autocomplete sends the typed city query and optional country filter to FreeAstroAPI over HTTPS when a visitor types at least two characters into the city field.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Natal chart, report, and simple-sign requests send the submitted birth data and calculation options to FreeAstroAPI over HTTPS when a logged-in user who can edit posts submits the form.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('These protected calculation requests include the site’s configured FreeAstroAPI key in a server-to-server request header.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” list is stored only in the visitor’s local browser storage.', 'astrology-suite-freeastroapi'); ?></li>
                </ul>
                <p><?php esc_html_e('For security, natal chart, report, and simple-sign calculations are limited to logged-in users who can edit posts on this site.', 'astrology-suite-freeastroapi'); ?></p>
                <p>
                    <a href="<?php echo esc_url(self::UPSTREAM_SERVICE_URL); ?>" target="_blank" rel="noopener"><?php esc_html_e('FreeAstroAPI website', 'astrology-suite-freeastroapi'); ?></a>
                    |
                    <a href="<?php echo esc_url(self::UPSTREAM_TERMS_URL); ?>" target="_blank" rel="noopener"><?php esc_html_e('Terms of Service', 'astrology-suite-freeastroapi'); ?></a>
                    |
                    <a href="<?php echo esc_url(self::UPSTREAM_PRIVACY_URL); ?>" target="_blank" rel="noopener"><?php esc_html_e('Privacy Policy', 'astrology-suite-freeastroapi'); ?></a>
                </p>

                <hr />
                <h2><?php esc_html_e('Support', 'astrology-suite-freeastroapi'); ?></h2>
                <p>
                    <a class="button button-primary" href="<?php echo esc_url(self::UPSTREAM_SUPPORT_URL); ?>" target="_blank" rel="noopener">
                        <?php esc_html_e('Contact FreeAstroAPI Support', 'astrology-suite-freeastroapi'); ?>
                    </a>
                </p>
                <hr />
                <h2><?php esc_html_e('Saved Inputs (Local Browser)', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('Astrology Suite for FreeAstroAPI can save your last 20 successful input requests in your current browser only.', 'astrology-suite-freeastroapi'); ?></p>
                <p><?php esc_html_e('Use the “Past inputs…” dropdown at the top-right of each widget form to quickly reload a previous chart request.', 'astrology-suite-freeastroapi'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    private static function count_legacy_block_posts(): int
    {
        $query = new \WP_Query([
            'post_type' => 'any',
            'post_status' => 'any',
            's' => 'wp:freeastro/',
            'posts_per_page' => 1,
            'no_found_rows' => false,
            'fields' => 'ids',
            'cache_results' => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);

        return (int) $query->found_posts;
    }

    public static function maybe_render_block_migration_notice(): void
    {
        if (!is_admin() || !current_user_can('edit_posts')) {
            return;
        }

        $migrated_param = filter_input(INPUT_GET, 'openastro_migrated', FILTER_SANITIZE_NUMBER_INT);
        if ($migrated_param !== null && $migrated_param !== false && $migrated_param !== '') {
            $count = max(0, (int) $migrated_param);
            /* translators: %d: Number of posts migrated from freeastro blocks to openastro blocks. */
            $migrated_message_format = __('Astrology Suite for FreeAstroAPI migrated %d post(s) to openastro/* blocks.', 'astrology-suite-freeastroapi');
            $migrated_message = sprintf($migrated_message_format, $count);
            echo '<div class="notice notice-success is-dismissible"><p>' .
                esc_html($migrated_message) .
                '</p></div>';
            return;
        }

        if (self::count_legacy_block_posts() < 1) {
            return;
        }

        $url = wp_nonce_url(admin_url('admin-post.php?action=openastro_migrate_blocks'), 'openastro_migrate_blocks');
        echo '<div class="notice notice-warning"><p>' .
            esc_html__('Legacy freeastro/* blocks were found. Migrate them to openastro/*.', 'astrology-suite-freeastroapi') .
            ' <a class="button button-secondary" href="' . esc_url($url) . '">' . esc_html__('Run migrator', 'astrology-suite-freeastroapi') . '</a></p></div>';
    }

    public static function handle_block_migration(): void
    {
        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('You are not allowed to run this migration.', 'astrology-suite-freeastroapi'));
        }

        check_admin_referer('openastro_migrate_blocks');

        $query = new \WP_Query([
            'post_type' => 'any',
            'post_status' => 'any',
            's' => 'wp:freeastro/',
            'posts_per_page' => -1,
            'no_found_rows' => true,
            'fields' => 'ids',
            'cache_results' => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);
        $posts = is_array($query->posts) ? $query->posts : [];

        $updated = 0;
        foreach ($posts as $post_id) {
            $content = (string) get_post_field('post_content', (int) $post_id);
            if (strpos($content, 'wp:freeastro/') === false && strpos($content, '/freeastro/') === false) {
                continue;
            }

            $new_content = str_replace(
                ['wp:freeastro/', '/wp-json/freeastro/', '"/freeastro/', '\\/freeastro/'],
                ['wp:openastro/', '/wp-json/openastro/', '"/openastro/', '\\/openastro/'],
                $content
            );

            if ($new_content === $content) {
                continue;
            }

            wp_update_post([
                'ID' => (int) $post_id,
                'post_content' => $new_content,
            ]);
            $updated++;
        }

        $redirect = add_query_arg('openastro_migrated', (string) $updated, admin_url('admin.php?page=openastro-settings'));
        wp_safe_redirect($redirect);
        exit;
    }

    public static function register_routes(): void
    {
        register_rest_route('openastro/v1', '/geo/search', [
            'methods' => \WP_REST_Server::READABLE,
            'callback' => [__CLASS__, 'geo_search'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('openastro/v1', '/natal/calculate', [
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [__CLASS__, 'natal_calculate'],
            'permission_callback' => [__CLASS__, 'calculation_permission'],
        ]);

        register_rest_route('openastro/v1', '/natal/chart-svg', [
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [__CLASS__, 'natal_chart_svg'],
            'permission_callback' => [__CLASS__, 'calculation_permission'],
        ]);

        register_rest_route('openastro/v1', '/western/simple-signs', [
            'methods' => \WP_REST_Server::CREATABLE,
            'callback' => [__CLASS__, 'simple_signs_calculate'],
            'permission_callback' => [__CLASS__, 'calculation_permission'],
        ]);
    }

    public static function calculation_permission()
    {
        if (!is_user_logged_in()) {
            return new \WP_Error(
                'rest_forbidden',
                __('Astrology Suite calculation requests require a logged-in user.', 'astrology-suite-freeastroapi'),
                ['status' => rest_authorization_required_code()]
            );
        }

        if (!self::current_user_can_calculate()) {
            return new \WP_Error(
                'rest_forbidden',
                __('Astrology Suite calculation requests require a user who can edit posts.', 'astrology-suite-freeastroapi'),
                ['status' => rest_authorization_required_code()]
            );
        }

        return true;
    }

    private static function ratelimit(string $bucket): bool
    {
        $key = 'openastro_rl_' . md5($bucket . '|' . self::client_fingerprint());
        $count = (int) get_transient($key);
        if ($count >= self::RATE_LIMIT_MAX_REQUESTS) {
            return false;
        }
        set_transient($key, $count + 1, self::RATE_LIMIT_WINDOW_SECONDS);
        return true;
    }

    private static function client_fingerprint(): string
    {
        $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
        $forwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR'])) : '';
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

        $xffIp = '';
        if ($forwardedFor !== '') {
            $parts = explode(',', $forwardedFor);
            if (isset($parts[0])) {
                $xffIp = trim($parts[0]);
            }
        }

        $ip = $xffIp !== '' ? $xffIp : $remoteAddr;
        return $ip . '|' . substr($userAgent, 0, 120);
    }

    public static function geo_search(\WP_REST_Request $request)
    {
        if (!self::ratelimit('geo_search')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $q = trim((string) $request->get_param('q'));
        $limit = (int) ($request->get_param('limit') ?: 10);
        $country = trim((string) $request->get_param('country'));

        if (strlen($q) < 2 || strlen($q) > 100) {
            return new \WP_Error('invalid_q', 'Query must be 2-100 chars.', ['status' => 400]);
        }
        if ($limit < 1 || $limit > 50) {
            return new \WP_Error('invalid_limit', 'Limit must be between 1 and 50.', ['status' => 400]);
        }

        $params = ['q' => $q, 'limit' => (string) $limit];
        if ($country !== '' && preg_match('/^[A-Za-z]{2}$/', $country)) {
            $params['country'] = strtoupper($country);
        }

        return self::request('GET', '/geo/search?' . http_build_query($params), null, false, false);
    }

    public static function natal_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('natal_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        $include_minor = !empty($payload['include_minor_aspects']) ? 'true' : 'false';
        return self::request('POST', '/natal/calculate?include_minor=' . $include_minor, $payload);
    }

    public static function natal_chart_svg(\WP_REST_Request $request)
    {
        if (!self::ratelimit('natal_chart_svg')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        $chart_payload = $payload;
        $chart_payload['format'] = 'svg';
        $chart_payload['size'] = 760;
        $chart_payload['theme_type'] = 'light';
        $chart_payload['show_metadata'] = false;
        $enabled_features = self::get_enabled_features();
        $chart_payload['display_settings'] = [
            'sun' => true, 'moon' => true, 'mercury' => true, 'venus' => true, 'mars' => true,
            'jupiter' => true, 'saturn' => true, 'uranus' => true, 'neptune' => true, 'pluto' => true,
            'asc' => in_array('asc', $enabled_features, true),
            'chiron' => in_array('chiron', $enabled_features, true),
            'lilith' => in_array('lilith', $enabled_features, true),
            'north_node' => in_array('true_node', $enabled_features, true) || in_array('mean_node', $enabled_features, true),
            'part_of_fortune' => in_array('part_of_fortune', $enabled_features, true),
        ];
        $chart_payload['chart_config'] = ['show_color_background' => false, 'custom_planet_color' => '#000000'];

        $include_minor = !empty($payload['include_minor_aspects']) ? 'true' : 'false';
        $result = self::request('POST', '/natal/experimental?include_minor=' . $include_minor, $chart_payload, true);

        if (is_wp_error($result)) {
            return $result;
        }

        return ['svg' => $result];
    }

    public static function simple_signs_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('simple_signs_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_simple_signs_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        $point = $payload['include_points'][0];
        $endpoint_point = $point === 'mc' ? 'midheaven' : $point;
        unset($payload['include_points']);

        $result = self::request('POST', '/western/signs/' . rawurlencode($endpoint_point), $payload);
        if (is_wp_error($result)) {
            return $result;
        }

        return self::normalize_simple_signs_response($result, $point);
    }

    private static function normalize_simple_signs_response(array $result, string $point): array
    {
        if (!isset($result['sign']) && !isset($result['point'])) {
            $keys = ['sun', 'moon', 'rising', 'midheaven', 'mc'];
            foreach ($keys as $k) {
                if (isset($result[$k]) && is_array($result[$k])) {
                    $result = $result[$k];
                    break;
                }
            }
        }

        $interpretation = null;
        if (isset($result['interpretation']) && is_array($result['interpretation'])) {
            $interpretation = [
                'title' => isset($result['interpretation']['title']) ? (string) $result['interpretation']['title'] : '',
                'body' => isset($result['interpretation']['body']) ? (string) $result['interpretation']['body'] : '',
                'tone' => isset($result['interpretation']['tone']) ? (string) $result['interpretation']['tone'] : '',
            ];
        }

        return [
            'point' => isset($result['point']) ? (string) $result['point'] : ucfirst($point),
            'sign' => isset($result['sign']) ? (string) $result['sign'] : '',
            'sign_id' => isset($result['sign_id']) ? (string) $result['sign_id'] : '',
            'degree' => isset($result['degree']) ? (float) $result['degree'] : null,
            'abs_pos' => isset($result['abs_pos']) ? (float) $result['abs_pos'] : null,
            'house' => isset($result['house']) ? (int) $result['house'] : null,
            'interpretation' => $interpretation,
            'raw' => $result,
        ];
    }

    private static function sanitize_simple_signs_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }

        $required = ['year', 'month', 'day', 'hour', 'minute', 'city', 'include_points'];
        foreach ($required as $field) {
            if (!isset($payload[$field])) {
                return new \WP_Error('invalid_payload', "Missing {$field}.", ['status' => 400]);
            }
        }

        if (!is_array($payload['include_points']) || count($payload['include_points']) !== 1) {
            return new \WP_Error('invalid_payload', 'include_points must contain exactly one point.', ['status' => 400]);
        }

        $point = strtolower(trim((string) $payload['include_points'][0]));
        if ($point === 'midheaven') {
            $point = 'mc';
        }
        if (!in_array($point, ['sun', 'moon', 'rising', 'mc'], true)) {
            return new \WP_Error('invalid_payload', 'Point must be one of sun, moon, rising, mc/midheaven.', ['status' => 400]);
        }

        $payload['year'] = (int) $payload['year'];
        $payload['month'] = (int) $payload['month'];
        $payload['day'] = (int) $payload['day'];
        $payload['hour'] = (int) $payload['hour'];
        $payload['minute'] = (int) $payload['minute'];

        if ($payload['year'] < 1800 || $payload['year'] > 2100) return new \WP_Error('invalid_payload', 'Year out of range.', ['status' => 400]);
        if ($payload['month'] < 1 || $payload['month'] > 12) return new \WP_Error('invalid_payload', 'Month out of range.', ['status' => 400]);
        if ($payload['day'] < 1 || $payload['day'] > 31) return new \WP_Error('invalid_payload', 'Day out of range.', ['status' => 400]);
        if ($payload['hour'] < 0 || $payload['hour'] > 23) return new \WP_Error('invalid_payload', 'Hour out of range.', ['status' => 400]);
        if ($payload['minute'] < 0 || $payload['minute'] > 59) return new \WP_Error('invalid_payload', 'Minute out of range.', ['status' => 400]);

        $payload['city'] = sanitize_text_field((string) $payload['city']);
        if ($payload['city'] === '') {
            return new \WP_Error('invalid_payload', 'City is required.', ['status' => 400]);
        }

        if (isset($payload['lat']) || isset($payload['lng'])) {
            if (!isset($payload['lat'], $payload['lng'])) {
                return new \WP_Error('invalid_payload', 'lat and lng must both be provided when overriding coordinates.', ['status' => 400]);
            }
            $payload['lat'] = (float) $payload['lat'];
            $payload['lng'] = (float) $payload['lng'];
            if ($payload['lat'] < -90 || $payload['lat'] > 90 || $payload['lng'] < -180 || $payload['lng'] > 180) {
                return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
            }
        }

        $payload['name'] = isset($payload['name']) ? sanitize_text_field((string) $payload['name']) : '';
        $payload['tz_str'] = isset($payload['tz_str']) ? sanitize_text_field((string) $payload['tz_str']) : 'AUTO';
        $payload['house_system'] = isset($payload['house_system']) ? sanitize_key((string) $payload['house_system']) : 'placidus';
        $payload['zodiac_type'] = isset($payload['zodiac_type']) ? sanitize_key((string) $payload['zodiac_type']) : 'tropical';
        $payload['include_points'] = [$point];

        $include_interpretation = !isset($payload['include_interpretation']) || (bool) $payload['include_interpretation'];
        $style = isset($payload['interpretation_style']) ? sanitize_key((string) $payload['interpretation_style']) : 'improved';
        if (!in_array($style, ['improved', 'poetic'], true)) {
            return new \WP_Error('invalid_payload', 'interpretation_style must be improved or poetic.', ['status' => 400]);
        }

        $payload['interpretation'] = ['enable' => $include_interpretation];
        if ($include_interpretation) {
            $payload['interpretation']['style'] = $style;
        }

        unset($payload['include_interpretation'], $payload['interpretation_style']);

        return $payload;
    }

    private static function sanitize_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }

        $required = ['year', 'month', 'day', 'hour', 'minute', 'city', 'lat', 'lng', 'tz_str'];
        foreach ($required as $field) {
            if (!isset($payload[$field])) {
                return new \WP_Error('invalid_payload', "Missing {$field}.", ['status' => 400]);
            }
        }

        $payload['year'] = (int) $payload['year'];
        $payload['month'] = (int) $payload['month'];
        $payload['day'] = (int) $payload['day'];
        $payload['hour'] = (int) $payload['hour'];
        $payload['minute'] = (int) $payload['minute'];
        $payload['lat'] = (float) $payload['lat'];
        $payload['lng'] = (float) $payload['lng'];

        if ($payload['year'] < 1800 || $payload['year'] > 2100) return new \WP_Error('invalid_payload', 'Year out of range.', ['status' => 400]);
        if ($payload['month'] < 1 || $payload['month'] > 12) return new \WP_Error('invalid_payload', 'Month out of range.', ['status' => 400]);
        if ($payload['day'] < 1 || $payload['day'] > 31) return new \WP_Error('invalid_payload', 'Day out of range.', ['status' => 400]);
        if ($payload['hour'] < 0 || $payload['hour'] > 23) return new \WP_Error('invalid_payload', 'Hour out of range.', ['status' => 400]);
        if ($payload['minute'] < 0 || $payload['minute'] > 59) return new \WP_Error('invalid_payload', 'Minute out of range.', ['status' => 400]);
        if ($payload['lat'] < -90 || $payload['lat'] > 90 || $payload['lng'] < -180 || $payload['lng'] > 180) {
            return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
        }

        $payload['name'] = isset($payload['name']) ? sanitize_text_field((string) $payload['name']) : '';
        $payload['city'] = sanitize_text_field((string) $payload['city']);
        $payload['tz_str'] = sanitize_text_field((string) $payload['tz_str']);
        $payload['house_system'] = 'placidus';
        $payload['zodiac_type'] = 'tropical';
        $payload['include_speed'] = true;
        $payload['include_dominants'] = true;
        $payload['include_features'] = self::get_enabled_features();
        $payload['interpretation'] = ['enable' => true, 'style' => 'improved'];
        $payload['include_minor_aspects'] = !isset($payload['include_minor_aspects']) || (bool) $payload['include_minor_aspects'];

        return $payload;
    }

    private static function request(string $method, string $path, ?array $body = null, bool $expect_svg = false, bool $include_api_key = true)
    {
        $args = [
            'method' => $method,
            'timeout' => 15,
            'headers' => [],
        ];

        if ($include_api_key) {
            $api_key = (string) self::get_option_value(self::OPTION_API_KEY, '');
            if ($api_key === '') {
                return new \WP_Error('missing_api_key', 'FreeAstroAPI key is not configured.', ['status' => 500]);
            }
            $args['headers']['x-api-key'] = $api_key;
        }

        if ($body !== null) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = wp_json_encode($body);
        }

        $response = wp_remote_request(self::UPSTREAM_BASE_URL . $path, $args);
        if (is_wp_error($response)) {
            return new \WP_Error('upstream_network_error', 'Could not reach upstream API.', ['status' => 502]);
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $raw = (string) wp_remote_retrieve_body($response);

        if ($status < 200 || $status >= 300) {
            return new \WP_Error('upstream_error', self::upstream_status_message($status), ['status' => $status]);
        }

        if ($expect_svg) {
            if (strpos($raw, '<svg') === false) {
                return new \WP_Error('invalid_svg', 'Invalid SVG response from upstream.', ['status' => 502]);
            }
            return $raw;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return new \WP_Error('invalid_response', 'Invalid JSON response from upstream.', ['status' => 502]);
        }

        return $decoded;
    }

    private static function upstream_status_message(int $status): string
    {
        if ($status === 400) return 'Upstream rejected the request payload.';
        if ($status === 401 || $status === 403) return 'Upstream authentication failed.';
        if ($status === 404) return 'Upstream endpoint not found.';
        if ($status === 408 || $status === 504) return 'Upstream request timed out.';
        if ($status === 429) return 'Upstream rate limit reached.';
        if ($status >= 500) return 'Upstream service is temporarily unavailable.';
        return 'Upstream API returned an error.';
    }

    public static function register_blocks(): void
    {
        self::register_widget_blocks();
    }

    private static function frontend_config(): array
    {
        return [
            'root' => esc_url_raw(rest_url('openastro/v1/')),
            'nonce' => wp_create_nonce('wp_rest'),
            'permissions' => [
                'canCalculate' => self::current_user_can_calculate(),
                'calculationMessage' => self::frontend_access_message(),
            ],
            'report' => [
                'detailLevel' => (string) self::get_option_value(self::OPTION_REPORT_DETAIL, 'full'),
                'maxPlanets' => (int) self::get_option_value(self::OPTION_REPORT_MAX_PLANETS, 13),
                'maxAspects' => (int) self::get_option_value(self::OPTION_REPORT_MAX_ASPECTS, 40),
                'showTables' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_TABLES, true),
                'showAngles' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_ANGLES, true),
                'showPrintButton' => (bool) self::get_option_value(self::OPTION_CHART_SHOW_PRINT_BUTTON, true),
            ],
            'objects' => [
                'asc' => (bool) self::get_option_value(self::OPTION_OBJ_ASC, true),
                'mc' => (bool) self::get_option_value(self::OPTION_OBJ_MC, true),
                'chiron' => (bool) self::get_option_value(self::OPTION_OBJ_CHIRON, true),
                'lilith' => (bool) self::get_option_value(self::OPTION_OBJ_LILITH, true),
                'true_node' => (bool) self::get_option_value(self::OPTION_OBJ_TRUE_NODE, true),
                'mean_node' => (bool) self::get_option_value(self::OPTION_OBJ_MEAN_NODE, true),
                'part_of_fortune' => (bool) self::get_option_value(self::OPTION_OBJ_PART_OF_FORTUNE, true),
            ],
            'style' => [
                'showBorder' => (bool) self::get_option_value(self::OPTION_STYLE_SHOW_BORDER, true),
                'backgroundColor' => (string) self::get_option_value(self::OPTION_STYLE_BG_COLOR, '#ffffff'),
                'textColor' => (string) self::get_option_value(self::OPTION_STYLE_TEXT_COLOR, '#111111'),
                'inputBorderRadius' => (int) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_RADIUS, 0),
                'inputBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_COLOR, '#000000'),
                'inputBgColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BG_COLOR, '#ffffff'),
                'inputTextColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_TEXT_COLOR, '#111111'),
                'inputPlaceholderColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, '#777777'),
                'buttonBorderRadius' => (int) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, 0),
                'buttonBgColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BG_COLOR, '#ffffff'),
                'buttonTextColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_TEXT_COLOR, '#000000'),
                'buttonBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_COLOR, '#000000'),
                'labelColor' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_COLOR, '#111111'),
                'labelWeight' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_WEIGHT, 'normal'),
                'focusRingColor' => (string) self::get_option_value(self::OPTION_STYLE_FOCUS_RING_COLOR, '#222222'),
                'astroFontSize' => (int) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_SIZE, 20),
                'astroFontColor' => (string) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_COLOR, '#111111'),
            ],
            'typography' => [
                'h2' => (int) self::get_option_value(self::OPTION_TYPO_H2, 28),
                'h3' => (int) self::get_option_value(self::OPTION_TYPO_H3, 22),
                'h4' => (int) self::get_option_value(self::OPTION_TYPO_H4, 18),
                'body' => (int) self::get_option_value(self::OPTION_TYPO_BODY, 16),
                'table' => (int) self::get_option_value(self::OPTION_TYPO_TABLE, 14),
                'input' => (int) self::get_option_value(self::OPTION_TYPO_INPUT, 18),
                'button' => (int) self::get_option_value(self::OPTION_TYPO_BUTTON, 20),
                'meta' => (int) self::get_option_value(self::OPTION_TYPO_META, 13),
            ],
        ];
    }

    private static function get_enabled_features(): array
    {
        $features = [];

        if ((bool) self::get_option_value(self::OPTION_OBJ_ASC, true)) { $features[] = 'asc'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_MC, true)) { $features[] = 'mc'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_CHIRON, true)) { $features[] = 'chiron'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_LILITH, true)) { $features[] = 'lilith'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_TRUE_NODE, true)) { $features[] = 'true_node'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_MEAN_NODE, true)) { $features[] = 'mean_node'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_PART_OF_FORTUNE, true)) { $features[] = 'part_of_fortune'; }

        if (count($features) < 2) {
            return ['asc', 'mc'];
        }

        return $features;
    }

    private static function add_config(string $handle): void
    {
        $config = wp_json_encode(self::frontend_config());
        wp_add_inline_script($handle, 'window.OpenAstroBlocks = Object.assign({}, window.OpenAstroBlocks || {}, ' . $config . ');', 'before');
    }

    private static function register_widget_blocks(): void
    {
        wp_register_script('openastro-suite-widgets-editor', OPENASTRO_SUITE_URL . 'assets/widgets-blocks.js', ['wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'], OPENASTRO_SUITE_VERSION, true);
        wp_register_script('openastro-suite-widgets-frontend', OPENASTRO_SUITE_URL . 'assets/widgets-frontend.js', [], OPENASTRO_SUITE_VERSION, true);
        wp_register_style('openastro-suite-widgets-style', OPENASTRO_SUITE_URL . 'assets/widgets-style.css', [], OPENASTRO_SUITE_VERSION);
        self::add_config('openastro-suite-widgets-frontend');

        $types = [
            'chart-only' => 'chart-only',
            'report-only' => 'report-only',
            'chart-report' => 'chart-report',
            'simple-sign-sun' => 'simple-sign-sun',
            'simple-sign-moon' => 'simple-sign-moon',
            'simple-sign-rising' => 'simple-sign-rising',
            'simple-sign-midheaven' => 'simple-sign-midheaven',
        ];

        foreach ($types as $slug => $_mode) {
            register_block_type('openastro/' . $slug, [
                'editor_script' => 'openastro-suite-widgets-editor',
                'script' => 'openastro-suite-widgets-frontend',
                'style' => 'openastro-suite-widgets-style',
            ]);
        }
    }
}
