<?php

namespace AstrologySuiteFreeAstroAPI;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private const VISITOR_RATE_LIMIT_MAX_REQUESTS = 30;
    private const VISITOR_GLOBAL_RATE_LIMIT_MAX_REQUESTS = 60;
    private const VISITOR_RATE_LIMIT_WINDOW_SECONDS = 60;
    private const UPSTREAM_MAX_RESPONSE_BYTES = 8388608;
    private const SVG_MAX_RESPONSE_BYTES = 3145728;
    private const SVG_MAX_NODES = 10000;
    private const SVG_MAX_DEPTH = 64;
    private const API_RPS_LIMIT_MIN = 1;
    private const API_RPS_LIMIT_MAX = 100;
    private const API_RATE_STATE_OPTION = 'openastro_api_next_request_at';
    private const ADMIN_PREVIEW_RATE_WAIT_SECONDS = 8;
    private const VEDIC_CALCULATION_DIVISIONS = [1, 2, 3, 7, 9, 10, 12, 30, 60];
    private const VEDIC_VISUAL_DIVISIONS = [1, 2, 3, 4, 5, 7, 9, 10, 12, 16, 20, 24, 27, 30, 40, 45, 60];
    private const UPSTREAM_BASE_URL = 'https://api.freeastroapi.com/api/v1';
    private const UPSTREAM_V2_BASE_URL = 'https://api.freeastroapi.com/api/v2';
    private const UPSTREAM_V3_BASE_URL = 'https://api.freeastroapi.com/api/v3';
    private const UPSTREAM_MCP_URL = 'https://api.freeastroapi.com/mcp';
    // phpcs:ignore PluginCheck.CodeAnalysis.AIProvider.DirectIntegration -- Required only as a fallback before the WordPress AI Client existed in WordPress 7.0.
    private const GEMINI_API_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta';
    private const DEFAULT_GEMINI_MODEL = 'gemini-3.1-flash-lite-preview';
    private const UPSTREAM_SERVICE_URL = 'https://www.freeastroapi.com/';
    private const UPSTREAM_TERMS_URL = 'https://www.freeastroapi.com/terms';
    private const UPSTREAM_PRIVACY_URL = 'https://www.freeastroapi.com/privacy';
    private const UPSTREAM_SUPPORT_URL = 'https://www.freeastroapi.com/contact';
    private const UPSTREAM_CHART_DESIGNER_URL = 'https://www.freeastroapi.com/dashboard/chart-designer';
    private const UPSTREAM_CHART_DOCS_URL = 'https://www.freeastroapi.com/docs/western/chart-svg';
    private const UPSTREAM_EPHEMERIS_DOCS_URL = 'https://www.freeastroapi.com/docs/western/ephemeris';
    private const UPSTREAM_VEDIC_VISUAL_DOCS_URL = 'https://www.freeastroapi.com/docs/vedic/visual-chart';
    private const UPSTREAM_VEDIC_CALCULATE_DOCS_URL = 'https://www.freeastroapi.com/docs/vedic/calculate';
    private const UPSTREAM_RATE_LIMITS_DOCS_URL = 'https://www.freeastroapi.com/docs/rate-limits';
    private const UPSTREAM_DAILY_SIGN_DOCS_URL = 'https://www.freeastroapi.com/docs/western/daily-sign';
    private const UPSTREAM_DAILY_PERSONAL_DOCS_URL = 'https://www.freeastroapi.com/docs/western/daily-personal';
    private const UPSTREAM_ASTROLOGY_BOT_URL = 'https://www.freeastroapi.com/astrology-bot';
    private const GEMINI_API_KEY_DOCS_URL = 'https://ai.google.dev/gemini-api/docs/api-key';
    private const CALCULATION_CAPABILITY = 'edit_posts';
    private const BLOCK_NAMESPACE = 'astrology-suite';
    private const LEGACY_BLOCK_NAMESPACE = 'openastro';
    private const REST_NAMESPACE = 'astrology-suite/v1';
    private const LEGACY_REST_NAMESPACE = 'openastro/v1';
    private const SETTINGS_PAGE_SLUG = 'astrology-suite-settings';
    private const LEGACY_SETTINGS_PAGE_SLUG = 'openastro-settings';
    private const SETTINGS_API_PAGE = 'openastro-settings';
    private const WIDGET_MODES = [
        'chart-only',
        'report-only',
        'chart-report',
        'simple-sign-sun',
        'simple-sign-moon',
        'simple-sign-rising',
        'simple-sign-midheaven',
        'ephemeris',
        'daily-horoscope',
        'personalized-horoscope',
        'vedic-chart',
        'vedic-workbench',
        'astrology-bot',
    ];

    public const OPTION_API_KEY = 'openastro_api_key';
    public const OPTION_API_RPS_LIMIT = 'openastro_api_rps_limit';
    public const OPTION_BOT_GEMINI_API_KEY = 'openastro_bot_gemini_api_key';
    public const OPTION_BOT_GEMINI_MODEL = 'openastro_bot_gemini_model';
    public const OPTION_PUBLIC_CALCULATIONS = 'openastro_public_calculations';
    public const OPTION_FRONTEND_LANGUAGE = 'openastro_frontend_language';
    public const OPTION_NATAL_RESPONSE_LANGUAGE = 'openastro_natal_response_language';
    public const OPTION_REPORT_DETAIL = 'openastro_report_detail';
    public const OPTION_REPORT_MAX_PLANETS = 'openastro_report_max_planets';
    public const OPTION_REPORT_MAX_ASPECTS = 'openastro_report_max_aspects';
    public const OPTION_REPORT_SHOW_TABLES = 'openastro_report_show_tables';
    public const OPTION_REPORT_SHOW_ANGLES = 'openastro_report_show_angles';
    public const OPTION_REPORT_SHOW_BIRTH_CONTEXT = 'openastro_report_show_birth_context';
    public const OPTION_REPORT_SHOW_PERSONALIZED_NOTES = 'openastro_report_show_personalized_notes';
    public const OPTION_REPORT_SHOW_ASPECT_GLYPHS = 'openastro_report_show_aspect_glyphs';
    public const OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION = 'openastro_report_show_thematic_interpretation';
    public const OPTION_CHART_SHOW_PRINT_BUTTON = 'openastro_chart_show_print_button';
    public const OPTION_CHART_THEME = 'openastro_chart_theme';
    public const OPTION_CHART_CUSTOM_CONFIG = 'openastro_chart_custom_config';
    public const OPTION_CHART_BODY_CURRENT = 'openastro_chart_body_current';
    public const OPTION_CHART_BODY_CLASSIC_MONO = 'openastro_chart_body_classic_mono';
    public const OPTION_CHART_BODY_TRANSPARENT_FRENCH = 'openastro_chart_body_transparent_french';
    public const OPTION_CHART_BODY_MIDNIGHT = 'openastro_chart_body_midnight';
    public const OPTION_CHART_BODY_PARCHMENT = 'openastro_chart_body_parchment';
    public const OPTION_CHART_BODY_CUSTOM = 'openastro_chart_body_custom';
    public const OPTION_WESTERN_HOUSE_SYSTEM = 'openastro_western_house_system';
    public const OPTION_WESTERN_NODE_TYPE = 'openastro_western_node_type';
    public const OPTION_VEDIC_CHART_SETTINGS = 'openastro_vedic_chart_settings';
    public const OPTION_VEDIC_SURFACE_BG_COLOR = 'openastro_vedic_surface_bg_color';
    public const OPTION_VEDIC_SURFACE_TEXT_COLOR = 'openastro_vedic_surface_text_color';
    public const OPTION_VEDIC_SURFACE_BORDER_COLOR = 'openastro_vedic_surface_border_color';
    public const OPTION_VEDIC_ACCENT_COLOR = 'openastro_vedic_accent_color';
    public const OPTION_VEDIC_TILE_BG_COLOR = 'openastro_vedic_tile_bg_color';
    public const OPTION_VEDIC_TILE_TEXT_COLOR = 'openastro_vedic_tile_text_color';
    public const OPTION_VEDIC_TILE_BORDER_COLOR = 'openastro_vedic_tile_border_color';
    public const OPTION_VEDIC_TABLE_HEADER_BG_COLOR = 'openastro_vedic_table_header_bg_color';
    public const OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR = 'openastro_vedic_table_header_text_color';
    public const OPTION_VEDIC_BADGE_BG_COLOR = 'openastro_vedic_badge_bg_color';
    public const OPTION_VEDIC_BADGE_TEXT_COLOR = 'openastro_vedic_badge_text_color';
    public const OPTION_OBJ_ASC = 'openastro_obj_asc';
    public const OPTION_OBJ_MC = 'openastro_obj_mc';
    public const OPTION_OBJ_CHIRON = 'openastro_obj_chiron';
    public const OPTION_OBJ_LILITH = 'openastro_obj_lilith';
    public const OPTION_OBJ_TRUE_NODE = 'openastro_obj_true_node';
    public const OPTION_OBJ_MEAN_NODE = 'openastro_obj_mean_node';
    public const OPTION_OBJ_PART_OF_FORTUNE = 'openastro_obj_part_of_fortune';
    public const OPTION_STYLE_SHOW_BORDER = 'openastro_style_show_border';
    public const OPTION_STYLE_BG_COLOR = 'openastro_style_bg_color';
    public const OPTION_STYLE_TRANSPARENT_BG = 'openastro_style_transparent_bg';
    public const OPTION_STYLE_TEXT_COLOR = 'openastro_style_text_color';
    public const OPTION_STYLE_SHOW_HISTORY = 'openastro_style_show_history';
    public const OPTION_STYLE_HISTORY_BG_COLOR = 'openastro_style_history_bg_color';
    public const OPTION_STYLE_HISTORY_BORDER_COLOR = 'openastro_style_history_border_color';
    public const OPTION_STYLE_HISTORY_TEXT_COLOR = 'openastro_style_history_text_color';
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
    public const OPTION_STYLE_MODAL_BG_COLOR = 'openastro_style_modal_bg_color';
    public const OPTION_STYLE_MODAL_TEXT_COLOR = 'openastro_style_modal_text_color';
    public const OPTION_STYLE_MODAL_BORDER_COLOR = 'openastro_style_modal_border_color';
    public const OPTION_STYLE_MODAL_ACCENT_COLOR = 'openastro_style_modal_accent_color';
    public const OPTION_EPHEMERIS_TABLE_BG_COLOR = 'openastro_ephemeris_table_bg_color';
    public const OPTION_EPHEMERIS_TABLE_TEXT_COLOR = 'openastro_ephemeris_table_text_color';
    public const OPTION_EPHEMERIS_TABLE_BORDER_COLOR = 'openastro_ephemeris_table_border_color';
    public const OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR = 'openastro_ephemeris_table_header_bg_color';
    public const OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR = 'openastro_ephemeris_table_header_text_color';
    public const OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR = 'openastro_ephemeris_table_badge_bg_color';
    public const OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR = 'openastro_ephemeris_table_badge_text_color';
    public const OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR = 'openastro_ephemeris_table_today_bg_color';
    public const OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR = 'openastro_ephemeris_table_today_accent_color';
    public const OPTION_EPHEMERIS_RETROGRADE_COLOR = 'openastro_ephemeris_retrograde_color';
    public const OPTION_EPHEMERIS_INGRESS_COLOR = 'openastro_ephemeris_ingress_color';
    public const OPTION_EPHEMERIS_STATION_COLOR = 'openastro_ephemeris_station_color';
    public const OPTION_HOROSCOPE_SHOW_THEME = 'openastro_horoscope_show_theme';
    public const OPTION_HOROSCOPE_SHOW_SUMMARY = 'openastro_horoscope_show_summary';
    public const OPTION_HOROSCOPE_SHOW_SCORES = 'openastro_horoscope_show_scores';
    public const OPTION_HOROSCOPE_SHOW_SCORE_FACTORS = 'openastro_horoscope_show_score_factors';
    public const OPTION_HOROSCOPE_SHOW_PERSONAL_CONTEXT = 'openastro_horoscope_show_personal_context';
    public const OPTION_HOROSCOPE_SHOW_LUCKY = 'openastro_horoscope_show_lucky';
    public const OPTION_HOROSCOPE_SHOW_GUIDANCE = 'openastro_horoscope_show_guidance';
    public const OPTION_HOROSCOPE_SHOW_ASTRO = 'openastro_horoscope_show_astro';
    public const OPTION_HOROSCOPE_CARD_BG_COLOR = 'openastro_horoscope_card_bg_color';
    public const OPTION_HOROSCOPE_CARD_TEXT_COLOR = 'openastro_horoscope_card_text_color';
    public const OPTION_HOROSCOPE_CARD_BORDER_COLOR = 'openastro_horoscope_card_border_color';
    public const OPTION_HOROSCOPE_THEME_BG_COLOR = 'openastro_horoscope_theme_bg_color';
    public const OPTION_HOROSCOPE_THEME_TEXT_COLOR = 'openastro_horoscope_theme_text_color';
    public const OPTION_HOROSCOPE_CHIP_BG_COLOR = 'openastro_horoscope_chip_bg_color';
    public const OPTION_HOROSCOPE_CHIP_TEXT_COLOR = 'openastro_horoscope_chip_text_color';
    public const OPTION_HOROSCOPE_CHIP_BORDER_COLOR = 'openastro_horoscope_chip_border_color';
    public const OPTION_HOROSCOPE_TILE_BG_COLOR = 'openastro_horoscope_tile_bg_color';
    public const OPTION_HOROSCOPE_TILE_TEXT_COLOR = 'openastro_horoscope_tile_text_color';
    public const OPTION_HOROSCOPE_TILE_BORDER_COLOR = 'openastro_horoscope_tile_border_color';
    public const OPTION_HOROSCOPE_ACCENT_COLOR = 'openastro_horoscope_accent_color';
    public const OPTION_BOT_SHELL_BG_COLOR = 'openastro_bot_shell_bg_color';
    public const OPTION_BOT_HEADER_BG_COLOR = 'openastro_bot_header_bg_color';
    public const OPTION_BOT_SCREEN_BG_COLOR = 'openastro_bot_screen_bg_color';
    public const OPTION_BOT_ACCENT_COLOR = 'openastro_bot_accent_color';
    public const OPTION_BOT_USER_BUBBLE_BG_COLOR = 'openastro_bot_user_bubble_bg_color';
    public const OPTION_BOT_USER_BUBBLE_TEXT_COLOR = 'openastro_bot_user_bubble_text_color';
    public const OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR = 'openastro_bot_assistant_bubble_bg_color';
    public const OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR = 'openastro_bot_assistant_bubble_text_color';
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
        add_action('admin_init', [__CLASS__, 'redirect_legacy_settings_request'], 1);
        add_action('admin_init', [__CLASS__, 'register_settings']);
        add_action('admin_init', [__CLASS__, 'add_privacy_policy_content']);
        add_action('admin_menu', [__CLASS__, 'register_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        add_action('admin_notices', [__CLASS__, 'maybe_render_block_migration_notice']);
        add_action('admin_post_openastro_migrate_blocks', [__CLASS__, 'handle_block_migration']);
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
        add_action('init', [__CLASS__, 'register_blocks']);
        add_action('init', [__CLASS__, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'maybe_enqueue_shortcode_assets']);
        add_action('wp_footer', [__CLASS__, 'maybe_print_late_shortcode_styles'], 1);
        add_action('wp_ajax_astrology_suite_chart_preview', [__CLASS__, 'render_chart_preview_image']);
        add_action('wp_ajax_astrology_suite_style_chart_preview_svg', [__CLASS__, 'render_style_chart_preview_svg']);
        add_action('wp_ajax_astrology_suite_vedic_chart_preview_svg', [__CLASS__, 'render_vedic_chart_preview_svg']);
        add_action('added_option', [__CLASS__, 'disable_secret_option_autoload'], 10, 2);
        add_filter('rest_post_dispatch', [__CLASS__, 'add_rest_security_headers'], 10, 3);
        add_filter('plugin_action_links_' . plugin_basename(OPENASTRO_SUITE_FILE), [__CLASS__, 'add_plugin_action_links']);
    }

    public static function add_plugin_action_links(array $links): array
    {
        $settings_url = admin_url('options-general.php?page=' . self::SETTINGS_PAGE_SLUG);
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
        $capability = apply_filters('openastro_suite_calculation_capability', self::CALCULATION_CAPABILITY);
        if (!is_string($capability) || sanitize_key($capability) !== $capability) {
            $capability = self::CALCULATION_CAPABILITY;
        }

        return current_user_can($capability);
    }

    private static function public_calculations_enabled(): bool
    {
        return (bool) self::get_option_value(self::OPTION_PUBLIC_CALCULATIONS, true);
    }

    private static function current_request_can_calculate(): bool
    {
        return self::public_calculations_enabled() || self::current_user_can_calculate();
    }

    private static function chart_theme_presets(): array
    {
        static $presets = null;
        if ($presets !== null) {
            return $presets;
        }

        $raw = [
            'current' => [
                'label' => __('Current Default', 'astrology-suite-freeastroapi'),
                'description' => __('The existing light chart styling used by the plugin.', 'astrology-suite-freeastroapi'),
                'json' => <<<'JSON'
{
  "show_color_background": false,
  "custom_planet_color": "#000000"
}
JSON
            ],
            'classic_mono' => [
                'label' => __('Classic Monochrome', 'astrology-suite-freeastroapi'),
                'description' => __('Clean white chart with dark glyphs, soft grey rings, and emphasized angle lines.', 'astrology-suite-freeastroapi'),
                'json' => <<<'JSON'
{
  "chart_background": "#FFFFFF",
  "custom_planet_color": "#000000",
  "custom_sign_color": "#000000",
  "custom_house_color": "#232323",
  "degree_label_color": "#444444",
  "custom_sign_bg_color": "#00000000",
  "custom_house_bg_color": "#FFFFFF",
  "custom_sign_colors": [
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000"
  ],
  "custom_planet_colors": {
    "asc": "#791a3e",
    "dsc": "#61177c",
    "mc": "#791a3e",
    "ic": "#5c0700"
  },
  "sign_line_color": "#C0C0C0",
  "house_line_color": "#C0C0C0",
  "sign_ring_inner_color": "#444444",
  "sign_ring_outer_color": "#444444",
  "house_ring_inner_color": "#C0C0C0",
  "house_ring_outer_color": "#C0C0C0",
  "asc_line_color": "#000000",
  "dsc_line_color": "#000000",
  "mc_line_color": "#000000",
  "ic_line_color": "#000000",
  "angle_label_color": "#444444",
  "asc_label_color": "#606060",
  "dsc_label_color": "#606060",
  "mc_label_color": "#606060",
  "ic_label_color": "#606060",
  "sign_tick_color": "#929292",
  "long_tick_color": "#C0C0C0",
  "short_tick_color": "#C0C0C0",
  "planet_connector_color": "#232323",
  "sign_ring_thickness_fraction": 0.17,
  "house_ring_thickness_fraction": 0.08,
  "center_disk_fraction": 0.42,
  "planet_symbol_scale": 0.41,
  "planet_spacing_scale": 1.25,
  "planet_radius_offset": -10,
  "round_planet_glyphs": false,
  "sign_symbol_scale": 0.8,
  "round_sign_wheel_glyphs": false,
  "house_number_scale": 0.3,
  "degree_label_scale": 0.6,
  "degree_label_minute_scale": 1.13,
  "degree_label_sign_scale": 1.31,
  "degree_label_row_gap": 1.95,
  "angle_label_scale": 0.67,
  "asc_label_scale": 0.67,
  "sign_line_width": 0.75,
  "house_line_width": 1.4,
  "sign_ring_inner_width": 1.4,
  "asc_line_width": 3,
  "dsc_line_width": 3,
  "mc_line_width": 3,
  "ic_line_width": 3,
  "sign_tick_width": 0.6,
  "long_sign_tick_width": 1,
  "sign_background_opacity": 1,
  "house_background_opacity": 0.71,
  "show_planet_connectors": true,
  "planet_connector_opacity": 0.24,
  "show_aspect_symbols": true,
  "show_aspect_lines": true,
  "show_house_ring": true,
  "show_house_numbers": true,
  "show_house_separators": true,
  "show_house_ring_circles": true,
  "show_sign_ticks": true,
  "show_retrograde_markers": true,
  "show_degree_labels": true,
  "show_angle_labels": true,
  "show_horoscope_shape": false,
  "horoscope_shape_style": "subtle",
  "horoscope_shape_label": false,
  "embed_horoscope_shape_metadata": false,
  "angle_marker_style": "french_arrow",
  "retrograde_marker_style": "rx",
  "degree_label_format": "minutes",
  "degree_label_precision": 1,
  "houses_inside_planets": true,
  "french_style": false,
  "show_color_background": true
}
JSON
            ],
            'transparent_french' => [
                'label' => __('Transparent French', 'astrology-suite-freeastroapi'),
                'description' => __('Transparent wheel with restrained technical lines and French-style labels.', 'astrology-suite-freeastroapi'),
                'json' => <<<'JSON'
{
  "chart_background": "#00000000",
  "custom_planet_color": "#262626",
  "custom_sign_color": "#111111",
  "custom_house_color": "#333333",
  "degree_label_color": "#262626",
  "custom_sign_bg_color": "#00000000",
  "custom_house_bg_color": "#00000000",
  "custom_sign_colors": [
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000"
  ],
  "custom_sign_glyph_colors": {},
  "custom_planet_colors": {},
  "custom_house_colors": [
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000"
  ],
  "sign_line_color": "#2F2F2F",
  "house_line_color": "#6B7280",
  "sign_ring_inner_color": "#111111",
  "sign_ring_outer_color": "#111111",
  "house_ring_inner_color": "#9CA3AF",
  "house_ring_outer_color": "#6B7280",
  "asc_line_color": "#111111",
  "dsc_line_color": "#111111",
  "mc_line_color": "#111111",
  "ic_line_color": "#111111",
  "sign_tick_color": "#111111",
  "long_tick_color": "#111111",
  "short_tick_color": "#111111",
  "planet_connector_color": "#EDE8F8",
  "aspect_conjunction_color": "#C08430",
  "aspect_opposition_color": "#B91C1C",
  "aspect_trine_color": "#2563EB",
  "aspect_square_color": "#DC2626",
  "aspect_sextile_color": "#15803D",
  "aspect_quincunx_color": "#7C3AED",
  "sign_ring_thickness_fraction": 0.2,
  "house_ring_thickness_fraction": 0.1,
  "center_disk_fraction": 0.38,
  "planet_symbol_scale": 0.62,
  "planet_radius_offset": 0,
  "round_planet_glyphs": false,
  "sign_symbol_scale": 0.72,
  "round_sign_wheel_glyphs": false,
  "house_number_scale": 0.38,
  "degree_label_scale": 0.58,
  "degree_label_degree_scale": 1,
  "degree_label_minute_scale": 1,
  "degree_label_sign_scale": 1.25,
  "degree_label_row_gap": 1.55,
  "french_planet_radius_offset": 0,
  "long_sign_tick_length": 8,
  "short_sign_tick_length": 3,
  "sign_line_width": 1,
  "house_line_width": 0.75,
  "sign_ring_inner_width": 1,
  "sign_ring_outer_width": 1,
  "house_ring_inner_width": 0.75,
  "house_ring_outer_width": 0.75,
  "asc_line_width": 1.5,
  "dsc_line_width": 1.5,
  "mc_line_width": 1.5,
  "ic_line_width": 1.5,
  "sign_tick_width": 0.35,
  "long_sign_tick_width": 1,
  "short_sign_tick_width": 0.35,
  "planet_connector_width": 1,
  "aspect_conjunction_width": 1.5,
  "aspect_opposition_width": 1.5,
  "aspect_trine_width": 1.25,
  "aspect_square_width": 1.35,
  "aspect_sextile_width": 1.15,
  "aspect_quincunx_width": 1,
  "sign_background_opacity": 0.18,
  "house_background_opacity": 0.1,
  "show_planet_connectors": true,
  "planet_connector_opacity": 0.6,
  "show_aspect_symbols": false,
  "show_aspect_lines": true,
  "show_house_ring": true,
  "show_house_numbers": true,
  "show_house_separators": true,
  "show_house_ring_circles": true,
  "show_sign_ticks": false,
  "show_retrograde_markers": true,
  "show_degree_labels": true,
  "show_angle_labels": true,
  "show_horoscope_shape": false,
  "horoscope_shape_style": "subtle",
  "horoscope_shape_color": "#D97706",
  "horoscope_shape_label": true,
  "embed_horoscope_shape_metadata": true,
  "show_pattern_overlays": false,
  "pattern_overlay_style": "subtle",
  "pattern_overlay_labels": false,
  "pattern_overlay_max_items": 4,
  "embed_pattern_metadata": false,
  "retrograde_marker_style": "R",
  "degree_label_format": "minutes",
  "degree_label_precision": 0,
  "houses_inside_planets": true,
  "french_style": true,
  "show_color_background": false
}
JSON
            ],
            'midnight' => [
                'label' => __('Midnight Zodiac', 'astrology-suite-freeastroapi'),
                'description' => __('Dark celestial chart with colored signs, violet rings, and bright glyphs.', 'astrology-suite-freeastroapi'),
                'json' => <<<'JSON'
{
  "chart_background": "#0B0710",
  "custom_planet_color": "#EDE8F8",
  "custom_sign_color": "#D8B96A",
  "custom_house_color": "#E5E7EB",
  "degree_label_color": "#EDE8F8",
  "custom_sign_bg_color": "#16111D",
  "custom_house_bg_color": "#00000000",
  "custom_sign_colors": [
    "#EF4444",
    "#D9A441",
    "#FACC15",
    "#38BDF8",
    "#F97316",
    "#34D399",
    "#FBBF24",
    "#A855F7",
    "#F43F5E",
    "#C08430",
    "#8B5CF6",
    "#60A5FA"
  ],
  "custom_sign_glyph_colors": {},
  "custom_planet_colors": {},
  "custom_house_colors": [
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000"
  ],
  "sign_line_color": "#8B6FB3",
  "house_line_color": "#8B6FB3",
  "sign_ring_inner_color": "#8B6FB3",
  "sign_ring_outer_color": "#8B6FB3",
  "house_ring_inner_color": "#8B6FB3",
  "house_ring_outer_color": "#8B6FB3",
  "asc_line_color": "#A78BFA",
  "dsc_line_color": "#A78BFA",
  "mc_line_color": "#A78BFA",
  "ic_line_color": "#A78BFA",
  "sign_tick_color": "#8B6FB3",
  "long_tick_color": "#8B6FB3",
  "short_tick_color": "#8B6FB3",
  "planet_connector_color": "#EDE8F8",
  "aspect_conjunction_color": "#FBBF24",
  "aspect_opposition_color": "#F472B6",
  "aspect_trine_color": "#3B82F6",
  "aspect_square_color": "#EF4444",
  "aspect_sextile_color": "#34D399",
  "aspect_quincunx_color": "#A78BFA",
  "sign_ring_thickness_fraction": 0.16,
  "house_ring_thickness_fraction": 0.05,
  "center_disk_fraction": 0.45,
  "planet_symbol_scale": 0.35,
  "planet_radius_offset": 0,
  "round_planet_glyphs": false,
  "sign_symbol_scale": 0.6,
  "round_sign_wheel_glyphs": false,
  "house_number_scale": 0.25,
  "degree_label_scale": 0.6,
  "degree_label_degree_scale": 1,
  "degree_label_minute_scale": 1,
  "degree_label_sign_scale": 1.25,
  "degree_label_row_gap": 1.55,
  "french_planet_radius_offset": 0,
  "long_sign_tick_length": 8,
  "short_sign_tick_length": 3,
  "sign_line_width": 1,
  "house_line_width": 0.75,
  "sign_ring_inner_width": 1,
  "sign_ring_outer_width": 1,
  "house_ring_inner_width": 0.75,
  "house_ring_outer_width": 0.75,
  "asc_line_width": 1.5,
  "dsc_line_width": 1.5,
  "mc_line_width": 1.5,
  "ic_line_width": 1.5,
  "sign_tick_width": 0.35,
  "long_sign_tick_width": 0.5,
  "short_sign_tick_width": 0.35,
  "planet_connector_width": 1,
  "aspect_conjunction_width": 1.5,
  "aspect_opposition_width": 1.5,
  "aspect_trine_width": 1.25,
  "aspect_square_width": 1.35,
  "aspect_sextile_width": 1.15,
  "aspect_quincunx_width": 1,
  "sign_background_opacity": 0.18,
  "house_background_opacity": 0.1,
  "show_planet_connectors": true,
  "planet_connector_opacity": 0.6,
  "show_aspect_symbols": false,
  "show_aspect_lines": true,
  "show_house_ring": true,
  "show_house_numbers": true,
  "show_house_separators": true,
  "show_house_ring_circles": true,
  "show_sign_ticks": true,
  "show_retrograde_markers": false,
  "show_degree_labels": true,
  "show_angle_labels": true,
  "show_horoscope_shape": false,
  "horoscope_shape_style": "subtle",
  "horoscope_shape_color": "#D97706",
  "horoscope_shape_label": true,
  "embed_horoscope_shape_metadata": true,
  "show_pattern_overlays": false,
  "pattern_overlay_style": "subtle",
  "pattern_overlay_labels": false,
  "pattern_overlay_max_items": 4,
  "embed_pattern_metadata": false,
  "retrograde_marker_style": "R",
  "degree_label_format": "degree",
  "degree_label_precision": 0,
  "houses_inside_planets": true,
  "french_style": false,
  "show_color_background": false
}
JSON
            ],
            'parchment' => [
                'label' => __('Parchment Gold', 'astrology-suite-freeastroapi'),
                'description' => __('Warm parchment palette with gold rings and earthy labels.', 'astrology-suite-freeastroapi'),
                'json' => <<<'JSON'
{
  "chart_background": "#F8F3EA",
  "custom_planet_color": "#211A16",
  "custom_sign_color": "#9A6B2F",
  "custom_house_color": "#3A332E",
  "degree_label_color": "#211A16",
  "custom_sign_bg_color": "#EFE3D1",
  "custom_house_bg_color": "#00000000",
  "custom_sign_colors": [
    "#EF4444",
    "#D9A441",
    "#FACC15",
    "#38BDF8",
    "#F97316",
    "#34D399",
    "#FBBF24",
    "#A855F7",
    "#F43F5E",
    "#C08430",
    "#8B5CF6",
    "#60A5FA"
  ],
  "custom_sign_glyph_colors": {},
  "custom_planet_colors": {},
  "custom_house_colors": [
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000",
    "#00000000"
  ],
  "sign_line_color": "#B98E54",
  "house_line_color": "#C8A66C",
  "sign_ring_inner_color": "#B98E54",
  "sign_ring_outer_color": "#7C5A2C",
  "house_ring_inner_color": "#D6BA85",
  "house_ring_outer_color": "#B98E54",
  "asc_line_color": "#6F4E21",
  "dsc_line_color": "#6F4E21",
  "mc_line_color": "#6F4E21",
  "ic_line_color": "#6F4E21",
  "sign_tick_color": "#7C5A2C",
  "long_tick_color": "#8B6FB3",
  "short_tick_color": "#8B6FB3",
  "planet_connector_color": "#EDE8F8",
  "aspect_conjunction_color": "#A16207",
  "aspect_opposition_color": "#B91C1C",
  "aspect_trine_color": "#1D4ED8",
  "aspect_square_color": "#DC2626",
  "aspect_sextile_color": "#15803D",
  "aspect_quincunx_color": "#7C3AED",
  "sign_ring_thickness_fraction": 0.16,
  "house_ring_thickness_fraction": 0.05,
  "center_disk_fraction": 0.45,
  "planet_symbol_scale": 0.35,
  "planet_radius_offset": 0,
  "round_planet_glyphs": false,
  "sign_symbol_scale": 0.6,
  "round_sign_wheel_glyphs": false,
  "house_number_scale": 0.25,
  "degree_label_scale": 0.6,
  "degree_label_degree_scale": 1,
  "degree_label_minute_scale": 1,
  "degree_label_sign_scale": 1.25,
  "degree_label_row_gap": 1.55,
  "french_planet_radius_offset": 0,
  "long_sign_tick_length": 8,
  "short_sign_tick_length": 3,
  "sign_line_width": 1,
  "house_line_width": 0.75,
  "sign_ring_inner_width": 1,
  "sign_ring_outer_width": 1,
  "house_ring_inner_width": 0.75,
  "house_ring_outer_width": 0.75,
  "asc_line_width": 1.5,
  "dsc_line_width": 1.5,
  "mc_line_width": 1.5,
  "ic_line_width": 1.5,
  "sign_tick_width": 0.35,
  "long_sign_tick_width": 0.5,
  "short_sign_tick_width": 0.35,
  "planet_connector_width": 1,
  "aspect_conjunction_width": 1.5,
  "aspect_opposition_width": 1.5,
  "aspect_trine_width": 1.25,
  "aspect_square_width": 1.35,
  "aspect_sextile_width": 1.15,
  "aspect_quincunx_width": 1,
  "sign_background_opacity": 0.2,
  "house_background_opacity": 0.12,
  "show_planet_connectors": true,
  "planet_connector_opacity": 0.6,
  "show_aspect_symbols": false,
  "show_aspect_lines": true,
  "show_house_ring": true,
  "show_house_numbers": true,
  "show_house_separators": true,
  "show_house_ring_circles": true,
  "show_sign_ticks": true,
  "show_retrograde_markers": false,
  "show_degree_labels": true,
  "show_angle_labels": true,
  "show_horoscope_shape": false,
  "horoscope_shape_style": "subtle",
  "horoscope_shape_color": "#D97706",
  "horoscope_shape_label": true,
  "embed_horoscope_shape_metadata": true,
  "show_pattern_overlays": false,
  "pattern_overlay_style": "subtle",
  "pattern_overlay_labels": false,
  "pattern_overlay_max_items": 4,
  "embed_pattern_metadata": false,
  "retrograde_marker_style": "R",
  "degree_label_format": "degree",
  "degree_label_precision": 0,
  "houses_inside_planets": true,
  "french_style": false,
  "show_color_background": false
}
JSON
            ],
        ];

        $presets = [];
        foreach ($raw as $slug => $preset) {
            $config = json_decode($preset['json'], true);
            $presets[$slug] = [
                'label' => $preset['label'],
                'description' => $preset['description'],
                'config' => is_array($config) ? self::normalize_chart_config_for_api($config) : [],
            ];
        }

        return $presets;
    }

    private static function custom_chart_config(): array
    {
        $raw = (string) self::get_option_value(self::OPTION_CHART_CUSTOM_CONFIG, '');
        if (trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) && !array_is_list($decoded) ? self::normalize_chart_config_for_api($decoded) : [];
    }

    private static function selected_chart_config(): array
    {
        $theme = (string) self::get_option_value(self::OPTION_CHART_THEME, 'current');
        $presets = self::chart_theme_presets();

        if ($theme === 'custom') {
            $custom = self::custom_chart_config();
            return $custom ?: $presets['current']['config'];
        }

        return isset($presets[$theme]) ? $presets[$theme]['config'] : $presets['current']['config'];
    }

    private static function chart_theme_body_option_names(): array
    {
        return [
            'current' => self::OPTION_CHART_BODY_CURRENT,
            'classic_mono' => self::OPTION_CHART_BODY_CLASSIC_MONO,
            'transparent_french' => self::OPTION_CHART_BODY_TRANSPARENT_FRENCH,
            'midnight' => self::OPTION_CHART_BODY_MIDNIGHT,
            'parchment' => self::OPTION_CHART_BODY_PARCHMENT,
            'custom' => self::OPTION_CHART_BODY_CUSTOM,
        ];
    }

    private static function selected_chart_body(): array
    {
        $theme = (string) self::get_option_value(self::OPTION_CHART_THEME, 'current');
        return self::chart_body_for_theme($theme);
    }

    private static function chart_body_for_theme(string $theme): array
    {
        $theme = self::sanitize_chart_theme($theme);
        $option_names = self::chart_theme_body_option_names();
        $option = $option_names[$theme] ?? self::OPTION_CHART_BODY_CURRENT;
        $raw = (string) self::get_option_value($option, '');
        if (trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && !array_is_list($decoded)) {
                return self::normalize_chart_body_for_api($decoded);
            }
        }

        return self::default_chart_body_for_theme($theme);
    }

    private static function default_chart_body_for_theme(string $theme): array
    {
        $theme = self::sanitize_chart_theme($theme);
        $theme_type = 'light';
        if ($theme === 'midnight') {
            $theme_type = 'dark';
        } elseif ($theme === 'classic_mono') {
            $theme_type = 'mono';
        }

        return [
            'size' => 760,
            'theme_type' => $theme_type,
            'show_metadata' => false,
            'display_settings' => self::chart_display_settings(),
            'chart_config' => self::chart_preview_config($theme),
        ];
    }

    private static function normalize_chart_body_for_api(array $body): array
    {
        foreach (['name', 'year', 'month', 'day', 'hour', 'minute', 'time_known', 'city', 'lat', 'lng', 'tz_str', 'house_system', 'zodiac_type', 'include_features'] as $birth_key) {
            unset($body[$birth_key]);
        }

        if (isset($body['chart_config']) && is_array($body['chart_config'])) {
            $body['chart_config'] = self::normalize_chart_config_for_api($body['chart_config']);
        }

        if (isset($body['display_settings']) && is_array($body['display_settings']) && $body['display_settings'] === []) {
            $body['display_settings'] = (object) [];
        }

        return $body;
    }

    private static function apply_chart_body(array $payload, array $body, string $format, ?int $forced_size = null, ?array $fallback_config = null): array
    {
        $format = $format === 'png' ? 'png' : 'svg';
        $body = self::normalize_chart_body_for_api($body);
        $payload = array_replace_recursive($payload, $body);
        $payload['format'] = $format;
        if ($forced_size !== null) {
            $payload['size'] = $forced_size;
        } else {
            $payload['size'] = max(240, min(1600, (int) ($payload['size'] ?? 760)));
        }
        if ($format === 'png') {
            $payload['png_quality_scale'] = max(1.0, min(5.0, (float) ($payload['png_quality_scale'] ?? 1.25)));
        }
        $theme_type = (string) ($payload['theme_type'] ?? 'light');
        $payload['theme_type'] = in_array($theme_type, ['light', 'dark', 'mono'], true) ? $theme_type : 'light';
        $payload['show_metadata'] = !empty($payload['show_metadata']);
        if (!isset($payload['display_settings']) || (!is_array($payload['display_settings']) && !is_object($payload['display_settings']))) {
            $payload['display_settings'] = self::chart_display_settings();
        }
        $display_settings = is_object($payload['display_settings'])
            ? (array) $payload['display_settings']
            : $payload['display_settings'];
        $managed_display_settings = self::chart_display_settings();
        foreach (['asc', 'mc', 'chiron', 'lilith', 'north_node', 'part_of_fortune'] as $managed_key) {
            $display_settings[$managed_key] = $managed_display_settings[$managed_key];
        }
        $payload['display_settings'] = $display_settings;
        if (empty($payload['chart_config']) || !is_array($payload['chart_config'])) {
            $payload['chart_config'] = $fallback_config ?? self::selected_chart_config();
        }
        $payload['chart_config'] = self::normalize_chart_config_for_api($payload['chart_config']);
        $payload['house_system'] = self::western_house_system();
        $payload['zodiac_type'] = 'tropical';
        $payload['include_features'] = self::get_enabled_features();

        return $payload;
    }

    public static function sanitize_western_house_system($value): string
    {
        $value = sanitize_key((string) $value);
        return in_array($value, ['placidus', 'whole_sign', 'equal', 'koch', 'regiomontanus', 'porphyry', 'campanus'], true)
            ? $value
            : 'placidus';
    }

    public static function sanitize_western_node_type($value): string
    {
        $value = sanitize_key((string) $value);
        return in_array($value, ['true', 'mean', 'none'], true) ? $value : 'true';
    }

    private static function western_house_system(): string
    {
        return self::sanitize_western_house_system(
            self::get_option_value(self::OPTION_WESTERN_HOUSE_SYSTEM, 'placidus')
        );
    }

    private static function western_node_type(): string
    {
        $saved = get_option(self::OPTION_WESTERN_NODE_TYPE, null);
        if ($saved !== null) {
            return self::sanitize_western_node_type($saved);
        }

        $true_node = (bool) self::get_option_value(self::OPTION_OBJ_TRUE_NODE, true);
        $mean_node = (bool) self::get_option_value(self::OPTION_OBJ_MEAN_NODE, true);
        if ($true_node) {
            return 'true';
        }
        return $mean_node ? 'mean' : 'none';
    }

    private static function chart_display_settings(): array
    {
        $enabled_features = self::get_enabled_features();
        return [
            'sun' => true,
            'moon' => true,
            'mercury' => true,
            'venus' => true,
            'mars' => true,
            'jupiter' => true,
            'saturn' => true,
            'uranus' => true,
            'neptune' => true,
            'pluto' => true,
            'asc' => in_array('asc', $enabled_features, true),
            'mc' => in_array('mc', $enabled_features, true),
            'chiron' => in_array('chiron', $enabled_features, true),
            'lilith' => in_array('lilith', $enabled_features, true),
            'north_node' => in_array('true_node', $enabled_features, true) || in_array('mean_node', $enabled_features, true),
            'part_of_fortune' => in_array('part_of_fortune', $enabled_features, true),
        ];
    }

    private static function normalize_chart_config_for_api(array $config): array
    {
        foreach (['custom_planet_colors', 'custom_sign_glyph_colors'] as $object_key) {
            if (array_key_exists($object_key, $config) && is_array($config[$object_key]) && $config[$object_key] === []) {
                $config[$object_key] = (object) [];
            }
        }

        return $config;
    }

    private static function vedic_chart_settings_defaults(): array
    {
        return [
            'default_preset' => 'guided',
            'chart_style' => 'north_indian',
            'theme_type' => 'light',
            'node_type' => 'mean',
            'ayanamsha' => 'lahiri',
            'sign_label_mode' => 'number_abbr',
            'body_label_mode' => 'abbr',
            'size' => 700,
            'default_divisions' => [1, 9],
            'dasha_levels' => 2,
            'include_yogas' => true,
            'include_panchang' => true,
            'include_shadbala' => false,
            'include_ashtakavarga' => false,
            'include_avastha' => false,
            'include_outer_planets' => false,
            'show_titles' => true,
            'show_subtitles' => true,
            'show_division_badge' => true,
            'custom_colors' => false,
            'background' => '#ffffff',
            'panel_background' => '#fff9f0',
            'panel_border' => '#d7c7ad',
            'grid' => '#b8791d',
            'outer_line' => '#7a1717',
            'sign_text' => '#7a1717',
            'house_text' => '#7a1717',
            'body_text' => '#111111',
            'ascendant_text' => '#0b7285',
            'brand_text' => '#7a1717',
            'title_text' => '#2f2418',
            'subtitle_text' => '#6b5b4a',
            'badge_background' => '#f2e5d4',
            'sign_font_size' => 0,
            'body_font_size' => 0,
            'grid_line_width' => 0,
            'outer_line_width' => 0,
            'panel_padding' => 0,
        ];
    }

    public static function sanitize_vedic_chart_settings($value): array
    {
        $defaults = self::vedic_chart_settings_defaults();
        $saved = self::get_option_value(self::OPTION_VEDIC_CHART_SETTINGS, $defaults);
        $raw = array_merge(
            $defaults,
            is_array($saved) ? $saved : [],
            is_array($value) ? $value : []
        );
        $clean = $defaults;
        $enums = [
            'default_preset' => ['guided', 'practitioner', 'custom'],
            'chart_style' => ['north_indian', 'south_indian', 'east_indian'],
            'theme_type' => ['light', 'dark', 'mono'],
            'node_type' => ['mean', 'true'],
            'ayanamsha' => ['lahiri'],
            'sign_label_mode' => ['number_abbr', 'number', 'abbr', 'astrofont', 'glyph', 'astrofont_number'],
            'body_label_mode' => ['abbr', 'glyph', 'astrofont', 'visual'],
        ];

        foreach ($enums as $key => $allowed) {
            $candidate = isset($raw[$key]) ? sanitize_key((string) $raw[$key]) : $defaults[$key];
            $clean[$key] = in_array($candidate, $allowed, true) ? $candidate : $defaults[$key];
        }

        $clean['size'] = max(360, min(900, (int) ($raw['size'] ?? $defaults['size'])));
        $requested_divisions = isset($raw['default_divisions']) && is_array($raw['default_divisions'])
            ? array_map('intval', $raw['default_divisions'])
            : $defaults['default_divisions'];
        $clean['default_divisions'] = array_values(array_intersect(
            self::VEDIC_CALCULATION_DIVISIONS,
            array_unique($requested_divisions)
        ));
        if ($clean['default_divisions'] === []) {
            $clean['default_divisions'] = $defaults['default_divisions'];
        }

        $clean['dasha_levels'] = max(0, min(3, (int) ($raw['dasha_levels'] ?? $defaults['dasha_levels'])));
        foreach (['include_yogas', 'include_panchang', 'include_shadbala', 'include_ashtakavarga', 'include_avastha', 'include_outer_planets', 'show_titles', 'show_subtitles', 'show_division_badge', 'custom_colors'] as $key) {
            $clean[$key] = filter_var($raw[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        foreach (['background', 'panel_background', 'panel_border', 'grid', 'outer_line', 'sign_text', 'house_text', 'body_text', 'ascendant_text', 'brand_text', 'title_text', 'subtitle_text', 'badge_background'] as $key) {
            $color = sanitize_hex_color((string) ($raw[$key] ?? $defaults[$key]));
            $clean[$key] = is_string($color) && $color !== '' ? strtolower($color) : $defaults[$key];
        }

        $clean['sign_font_size'] = max(0, min(64, (int) ($raw['sign_font_size'] ?? 0)));
        $clean['body_font_size'] = max(0, min(64, (int) ($raw['body_font_size'] ?? 0)));
        $clean['grid_line_width'] = max(0, min(12, (int) ($raw['grid_line_width'] ?? 0)));
        $clean['outer_line_width'] = max(0, min(12, (int) ($raw['outer_line_width'] ?? 0)));
        $clean['panel_padding'] = max(0, min(60, (int) ($raw['panel_padding'] ?? 0)));

        return $clean;
    }

    private static function vedic_chart_settings(): array
    {
        return self::sanitize_vedic_chart_settings(
            self::get_option_value(self::OPTION_VEDIC_CHART_SETTINGS, self::vedic_chart_settings_defaults())
        );
    }

    private static function vedic_chart_display_settings(array $settings, bool $include_outer_planets): array
    {
        return [
            'titles' => !empty($settings['show_titles']),
            'subtitles' => !empty($settings['show_subtitles']),
            'division_badge' => !empty($settings['show_division_badge']),
            'ascendant' => true,
            'grahas' => true,
            'rahu' => true,
            'ketu' => true,
            'sign_labels' => true,
            'house_labels' => false,
            'uranus' => $include_outer_planets,
            'neptune' => $include_outer_planets,
            'pluto' => $include_outer_planets,
        ];
    }

    private static function vedic_chart_config_for_api(array $settings, string $sign_label_mode, string $body_label_mode): array
    {
        $config = [
            'sign_label_mode' => $sign_label_mode,
            'body_label_mode' => $body_label_mode,
        ];
        $numeric_fields = [
            'sign_font_size' => 'sign_font_size',
            'body_font_size' => 'body_font_size',
            'grid_line_width' => 'grid_line_width',
            'outer_line_width' => 'outer_line_width',
            'panel_padding' => 'panel_padding',
        ];

        foreach ($numeric_fields as $setting_key => $api_key) {
            $value = (int) ($settings[$setting_key] ?? 0);
            if ($value > 0) {
                $config[$api_key] = $value;
            }
        }

        return $config;
    }

    private static function vedic_custom_theme_for_api(array $settings): array
    {
        if (empty($settings['custom_colors'])) {
            return [];
        }

        return [
            'background' => $settings['background'],
            'panel_background' => $settings['panel_background'],
            'panel_border' => $settings['panel_border'],
            'grid' => $settings['grid'],
            'outer_line' => $settings['outer_line'],
            'sign_text' => $settings['sign_text'],
            'house_text' => $settings['house_text'],
            'body_text' => $settings['body_text'],
            'ascendant_text' => $settings['ascendant_text'],
            'brand_text' => $settings['brand_text'],
            'title_text' => $settings['title_text'],
            'subtitle_text' => $settings['subtitle_text'],
            'badge_background' => $settings['badge_background'],
        ];
    }

    private static function frontend_access_message(): string
    {
        return __('This astrology form is disabled for public visitors. Enable public calculations in Astrology Suite API settings, or log in with permission to edit posts.', 'astrology-suite-freeastroapi');
    }

    public static function disable_secret_option_autoload(string $option, $value): void
    {
        if (!in_array($option, [self::OPTION_API_KEY, self::OPTION_BOT_GEMINI_API_KEY], true)) {
            return;
        }

        wp_set_option_autoload_values([$option => false]);
    }

    private static function harden_secret_option_autoload(): void
    {
        if ((string) get_option('openastro_security_hardening_version', '') === '1') {
            return;
        }

        wp_set_option_autoload_values([
            self::OPTION_API_KEY => false,
            self::OPTION_BOT_GEMINI_API_KEY => false,
        ]);
        update_option('openastro_security_hardening_version', '1', false);
    }

    public static function migrate_legacy_options(): void
    {
        self::harden_secret_option_autoload();

        if ((bool) get_option('openastro_migration_done', false)) {
            return;
        }

        $keys = [
            self::OPTION_API_KEY,
            self::OPTION_BOT_GEMINI_API_KEY,
            self::OPTION_BOT_GEMINI_MODEL,
            self::OPTION_PUBLIC_CALCULATIONS,
            self::OPTION_FRONTEND_LANGUAGE,
            self::OPTION_NATAL_RESPONSE_LANGUAGE,
            self::OPTION_REPORT_DETAIL,
            self::OPTION_REPORT_MAX_PLANETS,
            self::OPTION_REPORT_MAX_ASPECTS,
            self::OPTION_REPORT_SHOW_TABLES,
            self::OPTION_REPORT_SHOW_ANGLES,
            self::OPTION_REPORT_SHOW_BIRTH_CONTEXT,
            self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES,
            self::OPTION_REPORT_SHOW_ASPECT_GLYPHS,
            self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION,
            self::OPTION_CHART_SHOW_PRINT_BUTTON,
            self::OPTION_CHART_THEME,
            self::OPTION_CHART_CUSTOM_CONFIG,
            self::OPTION_CHART_BODY_CURRENT,
            self::OPTION_CHART_BODY_CLASSIC_MONO,
            self::OPTION_CHART_BODY_TRANSPARENT_FRENCH,
            self::OPTION_CHART_BODY_MIDNIGHT,
            self::OPTION_CHART_BODY_PARCHMENT,
            self::OPTION_CHART_BODY_CUSTOM,
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
            self::OPTION_STYLE_MODAL_BG_COLOR,
            self::OPTION_STYLE_MODAL_TEXT_COLOR,
            self::OPTION_STYLE_MODAL_BORDER_COLOR,
            self::OPTION_STYLE_MODAL_ACCENT_COLOR,
            self::OPTION_BOT_SHELL_BG_COLOR,
            self::OPTION_BOT_HEADER_BG_COLOR,
            self::OPTION_BOT_SCREEN_BG_COLOR,
            self::OPTION_BOT_ACCENT_COLOR,
            self::OPTION_BOT_USER_BUBBLE_BG_COLOR,
            self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR,
            self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR,
            self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR,
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

    public static function sanitize_api_key($value): string
    {
        $raw = trim((string) wp_unslash($value));
        if ($raw === '') {
            return (string) get_option(self::OPTION_API_KEY, '');
        }
        if (strlen($raw) > 512) {
            add_settings_error(self::OPTION_API_KEY, 'api_key_too_long', __('The FreeAstroAPI key is too long.', 'astrology-suite-freeastroapi'));
            return (string) get_option(self::OPTION_API_KEY, '');
        }

        return sanitize_text_field($raw);
    }

    public static function sanitize_api_rps_limit($value): int
    {
        return max(self::API_RPS_LIMIT_MIN, min(self::API_RPS_LIMIT_MAX, (int) $value));
    }

    private static function configured_api_rps_limit(): int
    {
        return self::sanitize_api_rps_limit(self::get_option_value(self::OPTION_API_RPS_LIMIT, 1));
    }

    public static function sanitize_bot_gemini_api_key($value): string
    {
        $raw = trim((string) wp_unslash($value));
        if ($raw === '') {
            return (string) get_option(self::OPTION_BOT_GEMINI_API_KEY, '');
        }
        if (strlen($raw) > 512) {
            add_settings_error(self::OPTION_BOT_GEMINI_API_KEY, 'gemini_api_key_too_long', __('The Gemini API key is too long.', 'astrology-suite-freeastroapi'));
            return (string) get_option(self::OPTION_BOT_GEMINI_API_KEY, '');
        }

        return sanitize_text_field($raw);
    }

    public static function sanitize_bot_gemini_model($value): string
    {
        $raw = trim((string) wp_unslash($value));
        if ($raw === '') {
            return self::DEFAULT_GEMINI_MODEL;
        }

        $clean = preg_replace('/[^A-Za-z0-9._:-]/', '', $raw);
        return $clean !== '' ? substr($clean, 0, 100) : self::DEFAULT_GEMINI_MODEL;
    }

    public static function sanitize_public_calculations($value): bool
    {
        return (bool) $value;
    }

    private static function supported_frontend_languages(): array
    {
        return [
            'auto' => __('Auto (site/browser language)', 'astrology-suite-freeastroapi'),
            'en' => __('English', 'astrology-suite-freeastroapi'),
            'vi' => __('Vietnamese', 'astrology-suite-freeastroapi'),
            'fr' => __('French', 'astrology-suite-freeastroapi'),
            'es' => __('Spanish', 'astrology-suite-freeastroapi'),
            'de' => __('German', 'astrology-suite-freeastroapi'),
            'pt' => __('Portuguese', 'astrology-suite-freeastroapi'),
        ];
    }

    private static function supported_natal_response_languages(): array
    {
        return [
            'auto' => __('Auto (match widget language when supported)', 'astrology-suite-freeastroapi'),
            'en' => __('English', 'astrology-suite-freeastroapi'),
            'fr' => __('French', 'astrology-suite-freeastroapi'),
            'es' => __('Spanish', 'astrology-suite-freeastroapi'),
            'tr' => __('Turkish', 'astrology-suite-freeastroapi'),
            'ru' => __('Russian', 'astrology-suite-freeastroapi'),
            'vi' => __('Vietnamese', 'astrology-suite-freeastroapi'),
            'pt' => __('Portuguese', 'astrology-suite-freeastroapi'),
        ];
    }

    public static function sanitize_natal_response_language($value): string
    {
        $candidate = self::normalize_natal_language_code((string) $value);
        if ($candidate === '') {
            $candidate = sanitize_key((string) $value);
        }

        return array_key_exists($candidate, self::supported_natal_response_languages()) ? $candidate : 'auto';
    }

    public static function sanitize_frontend_language($value): string
    {
        $candidate = sanitize_key((string) $value);
        return array_key_exists($candidate, self::supported_frontend_languages()) ? $candidate : 'auto';
    }

    private static function normalize_frontend_language(string $value): string
    {
        return self::sanitize_frontend_language($value);
    }

    private static function normalize_natal_language_code(string $value): string
    {
        $candidate = strtolower(str_replace('_', '-', trim(sanitize_text_field($value))));
        if ($candidate === '') {
            return '';
        }

        $base = explode('-', $candidate)[0];
        return array_key_exists($base, self::supported_natal_response_languages()) && $base !== 'auto' ? $base : '';
    }

    private static function resolve_natal_response_language(?string $requested = null): string
    {
        $setting = self::sanitize_natal_response_language((string) self::get_option_value(self::OPTION_NATAL_RESPONSE_LANGUAGE, 'auto'));
        if ($setting !== 'auto') {
            return $setting;
        }

        $requested_lang = $requested !== null ? self::normalize_natal_language_code($requested) : '';
        return $requested_lang !== '' ? $requested_lang : 'en';
    }

    private static function apply_natal_response_language(array $payload, ?string $requested = null): array
    {
        $candidate = $requested;
        if ($candidate === null) {
            $candidate = isset($payload['language']) ? (string) $payload['language'] : (isset($payload['lang']) ? (string) $payload['lang'] : null);
        }

        $language = self::resolve_natal_response_language($candidate);
        unset($payload['language']);
        $payload['lang'] = $language;

        if (isset($payload['interpretation']) && is_array($payload['interpretation']) && ($payload['interpretation']['enable'] ?? true)) {
            $payload['interpretation']['language'] = $language;
            if ($language !== 'en') {
                $payload['interpretation']['style'] = 'improved';
            }
        }

        return $payload;
    }

    public static function sanitize_chart_theme($value): string
    {
        $candidate = sanitize_key((string) $value);
        $allowed = array_merge(array_keys(self::chart_theme_presets()), ['custom']);
        return in_array($candidate, $allowed, true) ? $candidate : 'current';
    }

    public static function sanitize_chart_custom_config($value): string
    {
        $raw = trim((string) wp_unslash($value));
        if ($raw === '') {
            return '';
        }

        if (strlen($raw) > 75000) {
            add_settings_error(
                self::OPTION_CHART_CUSTOM_CONFIG,
                'chart_custom_config_too_large',
                __('Custom chart JSON is too large. Paste only the chart_config object from the chart designer.', 'astrology-suite-freeastroapi')
            );
            return (string) get_option(self::OPTION_CHART_CUSTOM_CONFIG, '');
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || array_is_list($decoded)) {
            add_settings_error(
                self::OPTION_CHART_CUSTOM_CONFIG,
                'chart_custom_config_invalid_json',
                __('Custom chart JSON must be a valid JSON object.', 'astrology-suite-freeastroapi')
            );
            return (string) get_option(self::OPTION_CHART_CUSTOM_CONFIG, '');
        }

        $clean = self::sanitize_chart_config_value($decoded);
        if (!is_array($clean) || array_is_list($clean)) {
            add_settings_error(
                self::OPTION_CHART_CUSTOM_CONFIG,
                'chart_custom_config_invalid_object',
                __('Custom chart JSON must decode to an object.', 'astrology-suite-freeastroapi')
            );
            return (string) get_option(self::OPTION_CHART_CUSTOM_CONFIG, '');
        }

        $clean = self::normalize_chart_config_for_api($clean);

        return (string) wp_json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public static function sanitize_chart_body_json($value): string
    {
        return self::sanitize_chart_body_json_for_option($value);
    }

    private static function sanitize_chart_body_json_for_option($value, ?string $option = null): string
    {
        if ($option !== null) {
            if (self::chart_body_reset_requested($option) || !self::chart_body_customize_requested($option)) {
                return '';
            }
        }

        $raw = trim((string) wp_unslash($value));
        if ($raw === '') {
            return '';
        }

        if (strlen($raw) > 120000) {
            add_settings_error(
                'openastro_chart_body',
                'chart_body_too_large',
                __('Chart body JSON is too large. Paste only the chart API body fields needed for this theme.', 'astrology-suite-freeastroapi')
            );
            return $option ? (string) get_option($option, '') : '';
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || array_is_list($decoded)) {
            add_settings_error(
                'openastro_chart_body',
                'chart_body_invalid_json',
                __('Chart body JSON must be a valid JSON object.', 'astrology-suite-freeastroapi')
            );
            return $option ? (string) get_option($option, '') : '';
        }

        $clean = self::sanitize_chart_config_value($decoded);
        if (!is_array($clean) || array_is_list($clean)) {
            add_settings_error(
                'openastro_chart_body',
                'chart_body_invalid_object',
                __('Chart body JSON must decode to an object.', 'astrology-suite-freeastroapi')
            );
            return $option ? (string) get_option($option, '') : '';
        }

        $clean = self::normalize_chart_body_for_api($clean);

        return (string) wp_json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private static function chart_body_customize_requested(string $option): bool
    {
        return self::posted_checkbox_is_checked($option . '_enabled');
    }

    private static function chart_body_reset_requested(string $option): bool
    {
        return self::posted_checkbox_is_checked($option . '_reset');
    }

    private static function posted_checkbox_is_checked(string $key): bool
    {
        // The Settings API checks the options.php nonce before registered sanitize callbacks run.
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (!isset($_POST[$key])) {
            return false;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reject arrays, then sanitize the scalar below.
        $posted_value = wp_unslash($_POST[$key]);
        if (is_array($posted_value)) {
            return false;
        }

        $value = sanitize_text_field((string) $posted_value);

        return in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
    }

    private static function sanitize_chart_config_value($value, int $depth = 0)
    {
        if ($depth > 8) {
            return null;
        }

        if (is_bool($value) || is_null($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return is_finite((float) $value) ? $value : 0;
        }

        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        if (!is_array($value)) {
            return null;
        }

        $clean = [];
        if (array_is_list($value)) {
            foreach (array_slice($value, 0, 48) as $item) {
                $clean[] = self::sanitize_chart_config_value($item, $depth + 1);
            }
            return $clean;
        }

        foreach (array_slice($value, 0, 160, true) as $key => $item) {
            $clean_key = sanitize_key((string) $key);
            if ($clean_key === '') {
                continue;
            }
            $clean[$clean_key] = self::sanitize_chart_config_value($item, $depth + 1);
        }

        return $clean;
    }

    public static function register_settings(): void
    {
        $api_group = self::settings_group_for_tab('api');
        $report_group = self::settings_group_for_tab('report');
        $chart_group = self::settings_group_for_tab('chart');
        $vedic_chart_group = self::settings_group_for_tab('vedic-chart');
        $objects_group = self::settings_group_for_tab('objects');
        $style_group = self::settings_group_for_tab('style');
        $ephemeris_group = self::settings_group_for_tab('ephemeris');
        $typography_group = self::settings_group_for_tab('typography');

        register_setting($api_group, self::OPTION_API_KEY, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_api_key'],
            'default' => '',
        ]);

        register_setting($api_group, self::OPTION_API_RPS_LIMIT, [
            'type' => 'integer',
            'sanitize_callback' => [__CLASS__, 'sanitize_api_rps_limit'],
            'default' => 1,
        ]);

        register_setting($api_group, self::OPTION_BOT_GEMINI_API_KEY, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_bot_gemini_api_key'],
            'default' => '',
        ]);

        register_setting($api_group, self::OPTION_BOT_GEMINI_MODEL, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_bot_gemini_model'],
            'default' => self::DEFAULT_GEMINI_MODEL,
        ]);

        register_setting($api_group, self::OPTION_PUBLIC_CALCULATIONS, [
            'type' => 'boolean',
            'sanitize_callback' => [__CLASS__, 'sanitize_public_calculations'],
            'default' => true,
        ]);

        register_setting($api_group, self::OPTION_FRONTEND_LANGUAGE, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_frontend_language'],
            'default' => 'auto',
        ]);

        register_setting($api_group, self::OPTION_NATAL_RESPONSE_LANGUAGE, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_natal_response_language'],
            'default' => 'auto',
        ]);

        register_setting($report_group, self::OPTION_REPORT_DETAIL, [
            'type' => 'string',
            'sanitize_callback' => static function ($value): string {
                $val = sanitize_key((string) $value);
                return in_array($val, ['full', 'concise'], true) ? $val : 'full';
            },
            'default' => 'full',
        ]);

        register_setting($report_group, self::OPTION_REPORT_MAX_PLANETS, [
            'type' => 'integer',
            'sanitize_callback' => static fn($v): int => max(1, min(20, (int) $v)),
            'default' => 13,
        ]);

        register_setting($report_group, self::OPTION_REPORT_MAX_ASPECTS, [
            'type' => 'integer',
            'sanitize_callback' => static fn($v): int => max(1, min(100, (int) $v)),
            'default' => 40,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_TABLES, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_ANGLES, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_BIRTH_CONTEXT, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_ASPECT_GLYPHS, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($report_group, self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => false,
        ]);

        register_setting($report_group, self::OPTION_CHART_SHOW_PRINT_BUTTON, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($chart_group, self::OPTION_CHART_THEME, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_chart_theme'],
            'default' => 'current',
        ]);

        register_setting($chart_group, self::OPTION_CHART_CUSTOM_CONFIG, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_chart_custom_config'],
            'default' => '',
        ]);

        foreach (self::chart_theme_body_option_names() as $body_option) {
            register_setting($chart_group, $body_option, [
                'type' => 'string',
                'sanitize_callback' => static fn($value): string => self::sanitize_chart_body_json_for_option($value, $body_option),
                'default' => '',
            ]);
        }

        register_setting($vedic_chart_group, self::OPTION_VEDIC_CHART_SETTINGS, [
            'type' => 'array',
            'sanitize_callback' => [__CLASS__, 'sanitize_vedic_chart_settings'],
            'default' => self::vedic_chart_settings_defaults(),
        ]);

        register_setting($objects_group, self::OPTION_OBJ_ASC, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_OBJ_MC, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_OBJ_CHIRON, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_OBJ_LILITH, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_WESTERN_HOUSE_SYSTEM, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_western_house_system'],
            'default' => 'placidus',
        ]);
        register_setting($objects_group, self::OPTION_WESTERN_NODE_TYPE, [
            'type' => 'string',
            'sanitize_callback' => [__CLASS__, 'sanitize_western_node_type'],
            'default' => 'true',
        ]);
        register_setting($objects_group, self::OPTION_OBJ_TRUE_NODE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_OBJ_MEAN_NODE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($objects_group, self::OPTION_OBJ_PART_OF_FORTUNE, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);

        register_setting($style_group, self::OPTION_STYLE_SHOW_BORDER, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($style_group, self::OPTION_STYLE_BG_COLOR, [
            'type' => 'string',
            'sanitize_callback' => static function ($v): string {
                $value = trim((string) $v);
                return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : '#ffffff';
            },
            'default' => '#ffffff',
        ]);
        register_setting($style_group, self::OPTION_STYLE_TRANSPARENT_BG, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => false,
        ]);
        register_setting($style_group, self::OPTION_STYLE_TEXT_COLOR, [
            'type' => 'string',
            'sanitize_callback' => static function ($v): string {
                $value = trim((string) $v);
                return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : '#111111';
            },
            'default' => '#111111',
        ]);
        register_setting($style_group, self::OPTION_STYLE_SHOW_HISTORY, [
            'type' => 'boolean',
            'sanitize_callback' => static fn($v): bool => (bool) $v,
            'default' => true,
        ]);
        register_setting($style_group, self::OPTION_STYLE_HISTORY_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#f9fafb']);
        register_setting($style_group, self::OPTION_STYLE_HISTORY_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#e5e7eb']);
        register_setting($style_group, self::OPTION_STYLE_HISTORY_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_INPUT_BORDER_RADIUS, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_radius_size'], 'default' => 6]);
        register_setting($style_group, self::OPTION_STYLE_INPUT_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#d1d5db']);
        register_setting($style_group, self::OPTION_STYLE_INPUT_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting($style_group, self::OPTION_STYLE_INPUT_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#6b7280']);
        register_setting($style_group, self::OPTION_STYLE_BUTTON_BORDER_RADIUS, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_radius_size'], 'default' => 6]);
        register_setting($style_group, self::OPTION_STYLE_BUTTON_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111827']);
        register_setting($style_group, self::OPTION_STYLE_BUTTON_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting($style_group, self::OPTION_STYLE_BUTTON_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111827']);
        register_setting($style_group, self::OPTION_STYLE_LABEL_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_LABEL_WEIGHT, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_label_weight'], 'default' => '600']);
        register_setting($style_group, self::OPTION_STYLE_FOCUS_RING_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#2563eb']);
        register_setting($style_group, self::OPTION_STYLE_ASTRO_FONT_SIZE, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 20]);
        register_setting($style_group, self::OPTION_STYLE_ASTRO_FONT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_MODAL_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting($style_group, self::OPTION_STYLE_MODAL_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_MODAL_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($style_group, self::OPTION_STYLE_MODAL_ACCENT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#d97706']);

        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#ffffff']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#e5e7eb']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#f8fafc']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#111111']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#f8fafc']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#475569']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#f8fafc']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#2563eb']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_RETROGRADE_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#b91c1c']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_INGRESS_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#1d4ed8']);
        register_setting($ephemeris_group, self::OPTION_EPHEMERIS_STATION_COLOR, ['type' => 'string', 'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'], 'default' => '#a16207']);

        foreach (self::horoscope_display_defaults() as $option => $default) {
            register_setting($style_group, $option, [
                'type' => 'boolean',
                'sanitize_callback' => static fn($v): bool => (bool) $v,
                'default' => $default,
            ]);
        }
        foreach (self::horoscope_style_defaults() as $option => $default) {
            register_setting($style_group, $option, [
                'type' => 'string',
                'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'],
                'default' => $default,
            ]);
        }
        foreach (self::bot_style_defaults() as $option => $default) {
            register_setting($style_group, $option, [
                'type' => 'string',
                'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'],
                'default' => $default,
            ]);
        }
        foreach (self::vedic_style_defaults() as $option => $default) {
            register_setting($vedic_chart_group, $option, [
                'type' => 'string',
                'sanitize_callback' => [__CLASS__, 'sanitize_hex_color_value'],
                'default' => $default,
            ]);
        }

        register_setting($typography_group, self::OPTION_TYPO_H2, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 28]);
        register_setting($typography_group, self::OPTION_TYPO_H3, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 22]);
        register_setting($typography_group, self::OPTION_TYPO_H4, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 18]);
        register_setting($typography_group, self::OPTION_TYPO_BODY, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 16]);
        register_setting($typography_group, self::OPTION_TYPO_TABLE, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 14]);
        register_setting($typography_group, self::OPTION_TYPO_INPUT, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 16]);
        register_setting($typography_group, self::OPTION_TYPO_BUTTON, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 16]);
        register_setting($typography_group, self::OPTION_TYPO_META, ['type' => 'integer', 'sanitize_callback' => [__CLASS__, 'sanitize_typography_size'], 'default' => 13]);

        add_settings_section('openastro_settings_main', __('Astrology Suite', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_report_settings', __('Report Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_chart_settings', __('Custom Chart Themes', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_vedic_essentials_settings', __('Vedic Essentials', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_vedic_chart_settings', __('Vedic Chart Settings', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_vedic_workbench_settings', __('Vedic Workbench Modules', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_vedic_appearance_settings', __('Vedic Result Appearance', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_vedic_preview_settings', __('Vedic Preview', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_objects_settings', __('Objects & Points', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_style_settings', __('Style Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_ephemeris_style_settings', __('Ephemeris Table Styling', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');
        add_settings_section('openastro_typography_settings', __('Typography Customization', 'astrology-suite-freeastroapi'), '__return_false', 'openastro-settings');

        add_settings_field(
            self::OPTION_API_KEY,
            __('API key', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_api_key_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_API_RPS_LIMIT,
            __('API requests per second', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_api_rps_limit_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_BOT_GEMINI_API_KEY,
            function_exists('wp_ai_client_prompt')
                ? __('Legacy bot Gemini API key (WordPress < 7.0)', 'astrology-suite-freeastroapi')
                : __('Bot Gemini API key', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_bot_gemini_api_key_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_BOT_GEMINI_MODEL,
            function_exists('wp_ai_client_prompt')
                ? __('Legacy bot Gemini model (WordPress < 7.0)', 'astrology-suite-freeastroapi')
                : __('Bot Gemini model', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_bot_gemini_model_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_PUBLIC_CALCULATIONS,
            __('Public calculations', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_public_calculations_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_FRONTEND_LANGUAGE,
            __('Frontend language', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_frontend_language_field'],
            'openastro-settings',
            'openastro_settings_main'
        );

        add_settings_field(
            self::OPTION_NATAL_RESPONSE_LANGUAGE,
            __('Natal response language', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_natal_response_language_field'],
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
            self::OPTION_REPORT_SHOW_BIRTH_CONTEXT,
            __('Show birth context', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_birth_context_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES,
            __('Show planet aspect links', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_personalized_notes_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_SHOW_ASPECT_GLYPHS,
            __('Show aspect glyphs', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_aspect_glyphs_field'],
            'openastro-settings',
            'openastro_report_settings'
        );

        add_settings_field(
            self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION,
            __('Show thematic interpretation', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_report_show_thematic_interpretation_field'],
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

        add_settings_field(
            self::OPTION_CHART_THEME,
            __('Chart theme', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_chart_theme_field'],
            'openastro-settings',
            'openastro_chart_settings'
        );

        add_settings_field(
            self::OPTION_CHART_CUSTOM_CONFIG,
            __('Advanced custom config', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_chart_custom_config_field'],
            'openastro-settings',
            'openastro_chart_settings'
        );

        add_settings_field(
            'openastro_chart_theme_bodies',
            __('Chart API bodies', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_chart_theme_bodies_field'],
            'openastro-settings',
            'openastro_chart_settings'
        );

        add_settings_field(
            self::OPTION_VEDIC_CHART_SETTINGS . '_essentials',
            __('Reading preset and calculation defaults', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_vedic_essentials_field'],
            'openastro-settings',
            'openastro_vedic_essentials_settings'
        );

        add_settings_field(
            self::OPTION_VEDIC_CHART_SETTINGS . '_charts',
            __('Chart defaults and appearance', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_vedic_chart_defaults_field'],
            'openastro-settings',
            'openastro_vedic_chart_settings'
        );

        add_settings_field(
            self::OPTION_VEDIC_CHART_SETTINGS . '_workbench',
            __('Default workbench modules', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_vedic_workbench_defaults_field'],
            'openastro-settings',
            'openastro_vedic_workbench_settings'
        );

        add_settings_field(
            'openastro_vedic_style_preview',
            __('Result style preview', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_vedic_style_preview_field'],
            'openastro-settings',
            'openastro_vedic_appearance_settings'
        );
        foreach ([
            self::OPTION_VEDIC_SURFACE_BG_COLOR => [__('Workbench surface background', 'astrology-suite-freeastroapi'), 'render_vedic_surface_bg_color_field'],
            self::OPTION_VEDIC_SURFACE_TEXT_COLOR => [__('Workbench text', 'astrology-suite-freeastroapi'), 'render_vedic_surface_text_color_field'],
            self::OPTION_VEDIC_SURFACE_BORDER_COLOR => [__('Workbench border', 'astrology-suite-freeastroapi'), 'render_vedic_surface_border_color_field'],
            self::OPTION_VEDIC_ACCENT_COLOR => [__('Vedic accent', 'astrology-suite-freeastroapi'), 'render_vedic_accent_color_field'],
            self::OPTION_VEDIC_TILE_BG_COLOR => [__('Summary tile background', 'astrology-suite-freeastroapi'), 'render_vedic_tile_bg_color_field'],
            self::OPTION_VEDIC_TILE_TEXT_COLOR => [__('Summary tile text', 'astrology-suite-freeastroapi'), 'render_vedic_tile_text_color_field'],
            self::OPTION_VEDIC_TILE_BORDER_COLOR => [__('Summary tile border', 'astrology-suite-freeastroapi'), 'render_vedic_tile_border_color_field'],
            self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR => [__('Table header background', 'astrology-suite-freeastroapi'), 'render_vedic_table_header_bg_color_field'],
            self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR => [__('Table header text', 'astrology-suite-freeastroapi'), 'render_vedic_table_header_text_color_field'],
            self::OPTION_VEDIC_BADGE_BG_COLOR => [__('Badge background', 'astrology-suite-freeastroapi'), 'render_vedic_badge_bg_color_field'],
            self::OPTION_VEDIC_BADGE_TEXT_COLOR => [__('Badge text', 'astrology-suite-freeastroapi'), 'render_vedic_badge_text_color_field'],
        ] as $option => $field) {
            add_settings_field($option, $field[0], [__CLASS__, $field[1]], 'openastro-settings', 'openastro_vedic_appearance_settings');
        }
        add_settings_field(
            'openastro_vedic_chart_preview',
            __('Generated chart preview', 'astrology-suite-freeastroapi'),
            [__CLASS__, 'render_vedic_chart_preview_field'],
            'openastro-settings',
            'openastro_vedic_preview_settings'
        );

        add_settings_field(self::OPTION_WESTERN_HOUSE_SYSTEM, __('Western house system', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_western_house_system_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_WESTERN_NODE_TYPE, __('Lunar node calculation', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_western_node_type_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_ASC, __('Include ASC', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_asc_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_MC, __('Include MC', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_mc_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_CHIRON, __('Include Chiron', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_chiron_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_LILITH, __('Include Lilith', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_lilith_field'], 'openastro-settings', 'openastro_objects_settings');
        add_settings_field(self::OPTION_OBJ_PART_OF_FORTUNE, __('Include Part of Fortune', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_obj_part_of_fortune_field'], 'openastro-settings', 'openastro_objects_settings');

        add_settings_field('openastro_style_preview', __('Preview', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_preview_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_SHOW_BORDER, __('Show container border', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_show_border_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_BG_COLOR, __('Plugin background color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_TRANSPARENT_BG, __('No plugin background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_transparent_bg_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_TEXT_COLOR, __('Plugin text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_history', __('Past inputs', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_history'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_SHOW_HISTORY, __('Show Past inputs', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_show_history_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_HISTORY_BG_COLOR, __('Past inputs background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_history_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_HISTORY_BORDER_COLOR, __('Past inputs border color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_history_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_HISTORY_TEXT_COLOR, __('Past inputs text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_history_text_color_field'], 'openastro-settings', 'openastro_style_settings');
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
        add_settings_field('openastro_style_group_modal', __('Aspect modal', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_modal'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_MODAL_BG_COLOR, __('Modal background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_modal_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_MODAL_TEXT_COLOR, __('Modal text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_modal_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_MODAL_BORDER_COLOR, __('Modal border color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_modal_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_STYLE_MODAL_ACCENT_COLOR, __('Modal link/accent color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_modal_accent_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_horoscope', __('Daily Sign Horoscope', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_horoscope'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_horoscope_preview', __('Preview', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_style_preview_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_horoscope_sections', __('Visible sections', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_sections_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CARD_BG_COLOR, __('Horoscope card background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_card_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CARD_TEXT_COLOR, __('Horoscope card text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_card_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CARD_BORDER_COLOR, __('Horoscope card border', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_card_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_THEME_BG_COLOR, __('Theme badge background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_theme_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_THEME_TEXT_COLOR, __('Theme badge text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_theme_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CHIP_BG_COLOR, __('Keyword/highlight chip background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_chip_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CHIP_TEXT_COLOR, __('Keyword/highlight chip text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_chip_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_CHIP_BORDER_COLOR, __('Keyword/highlight chip border', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_chip_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_TILE_BG_COLOR, __('Score/lucky tile background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_tile_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_TILE_TEXT_COLOR, __('Score/lucky tile text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_tile_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_TILE_BORDER_COLOR, __('Score/lucky tile border', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_tile_border_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_HOROSCOPE_ACCENT_COLOR, __('Score explanation accent', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_horoscope_accent_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_style_group_bot', __('Astrology Bot', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_style_group_bot'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_bot_preview', __('Preview', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_style_preview_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_SHELL_BG_COLOR, __('Bot shell background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_shell_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_HEADER_BG_COLOR, __('Bot header background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_header_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_SCREEN_BG_COLOR, __('Bot screen background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_screen_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_ACCENT_COLOR, __('Bot accent color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_accent_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_USER_BUBBLE_BG_COLOR, __('User bubble background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_user_bubble_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR, __('User bubble text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_user_bubble_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR, __('Assistant bubble background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_assistant_bubble_bg_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field(self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR, __('Assistant bubble text', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_bot_assistant_bubble_text_color_field'], 'openastro-settings', 'openastro_style_settings');
        add_settings_field('openastro_ephemeris_preview', __('Preview', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_style_preview_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_BG_COLOR, __('Table background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_bg_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR, __('Table text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_text_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR, __('Grid/border color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_border_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR, __('Header background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_header_bg_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR, __('Header text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_header_text_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR, __('Rows badge background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_badge_bg_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR, __('Rows badge text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_badge_text_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR, __('Current day row background', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_today_bg_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR, __('Current day accent color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_table_today_accent_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_RETROGRADE_COLOR, __('Retrograde text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_retrograde_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_INGRESS_COLOR, __('Ingress text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_ingress_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
        add_settings_field(self::OPTION_EPHEMERIS_STATION_COLOR, __('Station text color', 'astrology-suite-freeastroapi'), [__CLASS__, 'render_ephemeris_station_color_field'], 'openastro-settings', 'openastro_ephemeris_style_settings');
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
            self::SETTINGS_PAGE_SLUG,
            [__CLASS__, 'render_settings_page']
        );

        add_options_page(
            __('Astrology Suite', 'astrology-suite-freeastroapi'),
            __('Astrology Suite', 'astrology-suite-freeastroapi'),
            'manage_options',
            self::LEGACY_SETTINGS_PAGE_SLUG,
            [__CLASS__, 'redirect_legacy_settings_page']
        );

        $page_param = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $current_page = is_string($page_param) ? sanitize_key(wp_unslash($page_param)) : '';
        if ($current_page !== self::LEGACY_SETTINGS_PAGE_SLUG) {
            remove_submenu_page('options-general.php', self::LEGACY_SETTINGS_PAGE_SLUG);
        }
    }

    public static function redirect_legacy_settings_page(): void
    {
        self::redirect_legacy_settings_request();
    }

    public static function enqueue_admin_assets(string $hook): void
    {
        if ($hook !== 'settings_page_' . self::SETTINGS_PAGE_SLUG) {
            return;
        }

        self::register_widget_assets();
        wp_enqueue_style('openastro-suite-widgets-style');
    }

    public static function redirect_legacy_settings_request(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $page_param = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!is_string($page_param) || sanitize_key(wp_unslash($page_param)) !== self::LEGACY_SETTINGS_PAGE_SLUG) {
            return;
        }

        $args = ['page' => self::SETTINGS_PAGE_SLUG];
        $tab_param = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (is_string($tab_param) && $tab_param !== '') {
            $args['tab'] = sanitize_key(wp_unslash($tab_param));
        }

        $migrated_param = filter_input(INPUT_GET, 'openastro_migrated', FILTER_SANITIZE_NUMBER_INT);
        if ($migrated_param !== null && $migrated_param !== false && $migrated_param !== '') {
            $args['astrology_suite_migrated'] = max(0, (int) $migrated_param);
        }

        wp_safe_redirect(add_query_arg($args, admin_url('options-general.php')));
        exit;
    }

    public static function render_api_key_field(): void
    {
        $has_key = (string) self::get_option_value(self::OPTION_API_KEY, '') !== '';
        echo '<input type="password" class="regular-text" name="' . esc_attr(self::OPTION_API_KEY) . '" value="" autocomplete="new-password" placeholder="' . esc_attr__('Leave blank to keep current key', 'astrology-suite-freeastroapi') . '" />';
        echo '<p class="description">' . esc_html($has_key ? __('A FreeAstroAPI key is currently saved. Leave this field blank to keep it, or enter a new key to replace it.', 'astrology-suite-freeastroapi') : __('No FreeAstroAPI key is currently saved. Enter one to enable chart/report calculations.', 'astrology-suite-freeastroapi')) . '</p>';
        echo '<p class="description">' . esc_html__('Get a free API key from freeastroapi.com.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('The key is used only in server-to-server requests to FreeAstroAPI. It is not exposed to frontend visitors.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_api_rps_limit_field(): void
    {
        $value = self::configured_api_rps_limit();
        echo '<input type="number" class="small-text" name="' . esc_attr(self::OPTION_API_RPS_LIMIT) . '" value="' . esc_attr((string) $value) . '" min="' . esc_attr((string) self::API_RPS_LIMIT_MIN) . '" max="' . esc_attr((string) self::API_RPS_LIMIT_MAX) . '" step="1" />';
        echo '<p class="description">' . wp_kses(
            sprintf(
                /* translators: %s: FreeAstroAPI rate-limit documentation URL. */
                __('Match this to the requests-per-second limit for the saved FreeAstroAPI key. Current published examples are Free: 1, Entry: 5, and High: 10. The plugin defaults to 1 and spaces authenticated API calls across the site. Check the <a href="%s" target="_blank" rel="noopener">current rate-limit documentation</a> when changing plans.', 'astrology-suite-freeastroapi'),
                esc_url(self::UPSTREAM_RATE_LIMITS_DOCS_URL)
            ),
            ['a' => ['href' => [], 'target' => [], 'rel' => []]]
        ) . '</p>';
    }

    public static function render_bot_gemini_api_key_field(): void
    {
        $has_key = (string) self::get_option_value(self::OPTION_BOT_GEMINI_API_KEY, '') !== '';
        $uses_wordpress_ai = function_exists('wp_ai_client_prompt');
        echo '<input type="password" class="regular-text" name="' . esc_attr(self::OPTION_BOT_GEMINI_API_KEY) . '" value="" autocomplete="new-password" placeholder="' . esc_attr__('Leave blank to keep current key', 'astrology-suite-freeastroapi') . '" />';
        $key_status = $uses_wordpress_ai
            ? ($has_key
                ? __('A legacy Gemini API key is saved for older WordPress versions. WordPress 7.0 and newer ignore it.', 'astrology-suite-freeastroapi')
                : __('No legacy Gemini API key is saved. WordPress 7.0 and newer do not require this field.', 'astrology-suite-freeastroapi'))
            : ($has_key
                ? __('A Gemini API key is currently saved for the Astrology Bot. Leave this field blank to keep it, or enter a new key to replace it.', 'astrology-suite-freeastroapi')
                : __('No Gemini API key is currently saved. Add one to enable the Astrology Bot block and shortcode.', 'astrology-suite-freeastroapi'));
        echo '<p class="description">' . esc_html($key_status) . '</p>';
        if ($uses_wordpress_ai) {
            echo '<p class="description"><strong>' . esc_html__('WordPress 7.0 and newer use the AI provider connected under Settings > Connectors.', 'astrology-suite-freeastroapi') . '</strong> ' . esc_html__('The field above is retained only as a fallback for older WordPress versions.', 'astrology-suite-freeastroapi') . '</p>';
        }
        $gemini_help = $uses_wordpress_ai
            /* translators: %s: WordPress connector settings or Google AI Studio API key docs URL. */
            ? __('The bot uses your saved FreeAstroAPI key for MCP tool calls and the Google AI connector for the answer layer. Configure that connector under <a href="%s" target="_blank" rel="noopener">Settings > Connectors</a>. Neither credential is exposed to visitors.', 'astrology-suite-freeastroapi')
            /* translators: %s: WordPress connector settings or Google AI Studio API key docs URL. */
            : __('The bot uses your saved FreeAstroAPI key for MCP tool calls and this Gemini key for the answer layer. Get a Gemini key from <a href="%s" target="_blank" rel="noopener">Google AI Studio</a>. Neither key is exposed to visitors.', 'astrology-suite-freeastroapi');
        echo '<p class="description">' . wp_kses(
            sprintf(
                $gemini_help,
                esc_url($uses_wordpress_ai ? admin_url('options-connectors.php') : self::GEMINI_API_KEY_DOCS_URL)
            ),
            ['a' => ['href' => [], 'target' => [], 'rel' => []]]
        ) . '</p>';
    }

    public static function render_bot_gemini_model_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_BOT_GEMINI_MODEL, self::DEFAULT_GEMINI_MODEL);
        echo '<input type="text" class="regular-text" name="' . esc_attr(self::OPTION_BOT_GEMINI_MODEL) . '" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html(function_exists('wp_ai_client_prompt') ? __('Used only by the legacy pre-WordPress 7.0 fallback. WordPress 7.0 and newer select the model through the Google connector.', 'astrology-suite-freeastroapi') : __('Default follows the FreeAstroAPI bot starter. Change it only if your Google AI Studio account uses a different Gemini model name.', 'astrology-suite-freeastroapi')) . '</p>';
    }

    public static function render_public_calculations_field(): void
    {
        $value = self::public_calculations_enabled();
        echo '<input type="hidden" name="' . esc_attr(self::OPTION_PUBLIC_CALCULATIONS) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_PUBLIC_CALCULATIONS) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Allow visitors who are not logged in to use astrology forms.', 'astrology-suite-freeastroapi') . '</label>';
        echo '<p class="description">' . esc_html__('Public requests still go through this WordPress site, use the saved API key server-side, and are rate-limited per visitor. Turn this off to restrict calculations to logged-in users who can edit posts.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_frontend_language_field(): void
    {
        $value = self::normalize_frontend_language((string) self::get_option_value(self::OPTION_FRONTEND_LANGUAGE, 'auto'));
        echo '<select name="' . esc_attr(self::OPTION_FRONTEND_LANGUAGE) . '">';
        foreach (self::supported_frontend_languages() as $code => $label) {
            echo '<option value="' . esc_attr($code) . '" ' . selected($value, $code, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Localizes the widget interface, labels, buttons, validation messages, and report section headings. Natal interpretation response language is controlled separately below.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('Shortcodes can override this per widget with language="vi", language="fr", language="es", language="de", or language="pt".', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_natal_response_language_field(): void
    {
        $value = self::sanitize_natal_response_language((string) self::get_option_value(self::OPTION_NATAL_RESPONSE_LANGUAGE, 'auto'));
        echo '<select name="' . esc_attr(self::OPTION_NATAL_RESPONSE_LANGUAGE) . '">';
        foreach (self::supported_natal_response_languages() as $code => $label) {
            echo '<option value="' . esc_attr($code) . '" ' . selected($value, $code, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Controls the language requested for natal interpretation responses. Auto follows the widget UI language when it is supported by FreeAstroAPI, otherwise English is used.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('Translated natal interpretations use FreeAstroAPI style="improved"; the plugin forces improved automatically for translated responses because other styles are English-only.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function add_privacy_policy_content(): void
    {
        if (!function_exists('wp_add_privacy_policy_content')) {
            return;
        }

        $policy = '<p>' . esc_html__('Astrology Suite for FreeAstroAPI connects to FreeAstroAPI to autocomplete cities and generate astrology calculations.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('When someone types into the city field, the plugin sends the typed city query and optional country filter to FreeAstroAPI over HTTPS so matching cities and timezones can be returned.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('When a visitor submits a natal chart, report, simple-sign, ephemeris, horoscope, Vedic chart/workbench, or astrology bot form while public calculations are enabled, or when an authenticated editor submits while public calculations are disabled, the plugin sends the submitted astrology data and calculation options to FreeAstroAPI over HTTPS. This may include the optional name field, birth date, birth time, city name, latitude, longitude, timezone, interpretation settings, selected bodies, zodiac type, horoscope dates, Vedic vargas/modules/layout, chat message/history/profile, and the requested calculation mode. The site\'s configured FreeAstroAPI key is also sent in the server-to-server request header and is not exposed to visitors.', 'astrology-suite-freeastroapi') . '</p>';
        $policy .= '<p>' . esc_html__('For the Astrology Bot, the plugin may also send the visitor\'s chat message, recent chat history, selected system, and submitted birth profile to Google Gemini over HTTPS so the chat answer can be generated. The configured Gemini API key is used only server-to-server and is not exposed to visitors.', 'astrology-suite-freeastroapi') . '</p>';
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

    public static function render_report_show_birth_context_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_BIRTH_CONTEXT, true);
        echo '<input type="hidden" name="' . esc_attr(self::OPTION_REPORT_SHOW_BIRTH_CONTEXT) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_BIRTH_CONTEXT) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Add a birth context card using the submitted date, 24-hour time, city, timezone, and coordinates.', 'astrology-suite-freeastroapi') . '</label>';
    }

    public static function render_report_show_personalized_notes_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES, true);
        echo '<input type="hidden" name="' . esc_attr(self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Add clickable aspect links inside each planet interpretation. The modal displays the matching aspect interpretation block returned by FreeAstroAPI.', 'astrology-suite-freeastroapi') . '</label>';
        echo '<p class="description">' . esc_html__('This uses the normal interpretation blocks only. It does not call or display the psychological report, and it does not request unsupported API interpretation languages.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_report_show_aspect_glyphs_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_ASPECT_GLYPHS, true);
        echo '<input type="hidden" name="' . esc_attr(self::OPTION_REPORT_SHOW_ASPECT_GLYPHS) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_ASPECT_GLYPHS) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Display astrology glyphs in planet aspect lists and the All Aspect Dynamics table.', 'astrology-suite-freeastroapi') . '</label>';
    }

    public static function render_report_show_thematic_interpretation_field(): void
    {
        $value = (bool) self::get_option_value(self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION, false);
        echo '<input type="hidden" name="' . esc_attr(self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr(self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html__('Add a Thematic Interpretation section grouped by FreeAstroAPI interpretation themes.', 'astrology-suite-freeastroapi') . '</label>';
        echo '<p class="description">' . esc_html__('This reuses the normal interpretation response grouped by theme. It does not call the psychological report endpoint.', 'astrology-suite-freeastroapi') . '</p>';
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

    public static function render_chart_theme_field(): void
    {
        $selected = (string) self::get_option_value(self::OPTION_CHART_THEME, 'current');
        $presets = self::chart_theme_presets();
        self::render_chart_theme_admin_styles();

        echo '<p class="description">' . esc_html__('Choose the chart request body sent to the FreeAstroAPI SVG/PNG chart endpoint.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('Western house system and object/node choices come from the Objects tab and are applied after custom chart body fields, keeping chart and report calculations consistent.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<p class="description">' . esc_html__('Preview images are PNG charts generated by FreeAstroAPI from sample birth data and each theme body.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<div class="astrology-suite-chart-theme-grid">';

        foreach ($presets as $slug => $preset) {
            self::render_chart_theme_card($slug, $preset['label'], $preset['description'], $selected === $slug);
        }

        self::render_chart_theme_card(
            'custom',
            __('Custom JSON', 'astrology-suite-freeastroapi'),
            __('Use the custom theme body below, or the custom chart_config field when no body is saved.', 'astrology-suite-freeastroapi'),
            $selected === 'custom'
        );

        echo '</div>';
    }

    public static function render_chart_custom_config_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_CHART_CUSTOM_CONFIG, '');
        ?>
        <details class="astrology-suite-chart-advanced">
            <summary><?php esc_html_e('Advanced: paste a custom chart_config JSON object', 'astrology-suite-freeastroapi'); ?></summary>
            <p class="description">
                <?php
                echo wp_kses(
                    sprintf(
                        /* translators: %s: FreeAstroAPI chart designer URL. */
                        __('Generate a custom chart config in the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Chart Designer</a>, then paste only the JSON object here. Select “Custom JSON” above to use it.', 'astrology-suite-freeastroapi'),
                        esc_url(self::UPSTREAM_CHART_DESIGNER_URL)
                    ),
                    ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                );
                ?>
            </p>
            <textarea
                name="<?php echo esc_attr(self::OPTION_CHART_CUSTOM_CONFIG); ?>"
                rows="18"
                class="large-text code"
                spellcheck="false"
                placeholder="<?php echo esc_attr__('Paste chart_config JSON here.', 'astrology-suite-freeastroapi'); ?>"
            ><?php echo esc_textarea($value); ?></textarea>
        </details>
        <?php
    }

    public static function render_chart_theme_bodies_field(): void
    {
        $presets = self::chart_theme_presets();
        $option_names = self::chart_theme_body_option_names();
        $labels = array_map(static fn($preset) => (string) $preset['label'], $presets);
        $labels['custom'] = __('Custom JSON', 'astrology-suite-freeastroapi');
        ?>
        <details class="astrology-suite-chart-advanced">
            <summary><?php esc_html_e('Advanced: customize the chart API body for each theme', 'astrology-suite-freeastroapi'); ?></summary>
            <p class="description">
                <?php
                echo wp_kses(
                    sprintf(
                        /* translators: %s: FreeAstroAPI chart docs URL. */
                        __('These are partial request bodies for <a href="%s" target="_blank" rel="noopener">FreeAstroAPI’s SVG/PNG chart endpoint</a>. The plugin still supplies the visitor’s birth data, forces SVG for frontend charts, and forces PNG for preview cards.', 'astrology-suite-freeastroapi'),
                        esc_url(self::UPSTREAM_CHART_DOCS_URL)
                    ),
                    ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                );
                ?>
            </p>
            <p class="description">
                <?php esc_html_e('Supported useful body fields include size, theme_type, png_quality_scale, show_metadata, display_settings, fixed_stars, and chart_config. Birth fields such as name, year, time_known, city, lat, and lng are ignored here.', 'astrology-suite-freeastroapi'); ?>
            </p>
            <div class="astrology-suite-chart-body-list">
                <?php foreach ($option_names as $theme => $option) : ?>
                    <?php
                    $saved = (string) self::get_option_value($option, '');
                    $is_custom = trim($saved) !== '';
                    $body = $is_custom ? $saved : (string) wp_json_encode(self::default_chart_body_for_theme($theme), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    $label = $labels[$theme] ?? $theme;
                    /* translators: %s: chart theme label. */
                    $summary_label = sprintf(__('%s chart body', 'astrology-suite-freeastroapi'), $label);
                    ?>
                    <details class="astrology-suite-chart-body-item">
                        <summary><?php echo esc_html($summary_label); ?></summary>
                        <p class="description">
                            <?php esc_html_e('Preset body shown below is not saved unless "Customize this body" is checked. Leave it unchecked to keep the theme preset and dynamic object/display fallbacks.', 'astrology-suite-freeastroapi'); ?>
                        </p>
                        <p>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr($option . '_enabled'); ?>" value="1" <?php checked($is_custom, true); ?> />
                                <?php esc_html_e('Customize this body', 'astrology-suite-freeastroapi'); ?>
                            </label>
                            &nbsp;
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr($option . '_reset'); ?>" value="1" />
                                <?php esc_html_e('Reset to preset on save', 'astrology-suite-freeastroapi'); ?>
                            </label>
                        </p>
                        <textarea
                            name="<?php echo esc_attr($option); ?>"
                            rows="14"
                            class="large-text code"
                            spellcheck="false"
                        ><?php echo esc_textarea($body); ?></textarea>
                    </details>
                <?php endforeach; ?>
            </div>
        </details>
        <?php
    }

    public static function render_vedic_essentials_field(): void
    {
        $settings = self::vedic_chart_settings();
        self::render_chart_theme_admin_styles();
        ?>
        <div class="astrology-suite-vedic-settings-grid">
            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Default reading experience', 'astrology-suite-freeastroapi'); ?></h3>
                <?php
                self::render_vedic_select_control('default_preset', __('Audience preset', 'astrology-suite-freeastroapi'), $settings, [
                    'guided' => __('Guided - D1/D9 essentials for visitors', 'astrology-suite-freeastroapi'),
                    'practitioner' => __('Practitioner - full reference modules', 'astrology-suite-freeastroapi'),
                    'custom' => __('Custom - use the saved chart and workbench selections', 'astrology-suite-freeastroapi'),
                ]);
                self::render_vedic_select_control('node_type', __('Lunar nodes', 'astrology-suite-freeastroapi'), $settings, [
                    'mean' => __('Mean Rahu / Ketu', 'astrology-suite-freeastroapi'),
                    'true' => __('True Rahu / Ketu', 'astrology-suite-freeastroapi'),
                ]);
                ?>
                <p class="description"><?php esc_html_e('The audience preset controls initial frontend choices and can be overridden for each Vedic block.', 'astrology-suite-freeastroapi'); ?></p>
            </section>
            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Calculation basis', 'astrology-suite-freeastroapi'); ?></h3>
                <p><strong><?php esc_html_e('Ayanamsha:', 'astrology-suite-freeastroapi'); ?></strong> <?php esc_html_e('Lahiri', 'astrology-suite-freeastroapi'); ?></p>
                <p class="description"><?php esc_html_e('Lahiri is the verified default exposed by the current FreeAstroAPI Vedic endpoints. Additional ayanamshas will be added only after their supported values are documented.', 'astrology-suite-freeastroapi'); ?></p>
                <p><strong><?php esc_html_e('House system:', 'astrology-suite-freeastroapi'); ?></strong> <?php esc_html_e('Whole Sign', 'astrology-suite-freeastroapi'); ?></p>
                <p class="description"><?php esc_html_e('Exact birth time is required for Lagna, bhavas, divisional charts, dashas, and timing references.', 'astrology-suite-freeastroapi'); ?></p>
            </section>
        </div>
        <?php
    }

    public static function render_vedic_chart_defaults_field(): void
    {
        $settings = self::vedic_chart_settings();
        self::render_chart_theme_admin_styles();
        ?>
        <div class="astrology-suite-vedic-settings-grid">
            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Default chart controls', 'astrology-suite-freeastroapi'); ?></h3>
                <?php
                self::render_vedic_select_control('chart_style', __('Kundli layout', 'astrology-suite-freeastroapi'), $settings, [
                    'north_indian' => __('North Indian', 'astrology-suite-freeastroapi'),
                    'south_indian' => __('South Indian', 'astrology-suite-freeastroapi'),
                    'east_indian' => __('East Indian', 'astrology-suite-freeastroapi'),
                ]);
                self::render_vedic_select_control('theme_type', __('Built-in theme', 'astrology-suite-freeastroapi'), $settings, [
                    'light' => __('Light', 'astrology-suite-freeastroapi'),
                    'dark' => __('Dark', 'astrology-suite-freeastroapi'),
                    'mono' => __('Monochrome', 'astrology-suite-freeastroapi'),
                ]);
                self::render_vedic_select_control('sign_label_mode', __('Sign labels', 'astrology-suite-freeastroapi'), $settings, [
                    'number_abbr' => __('Number + abbreviation', 'astrology-suite-freeastroapi'),
                    'number' => __('Numbers', 'astrology-suite-freeastroapi'),
                    'abbr' => __('Abbreviations', 'astrology-suite-freeastroapi'),
                    'astrofont_number' => __('Glyph + number', 'astrology-suite-freeastroapi'),
                    'glyph' => __('Glyphs', 'astrology-suite-freeastroapi'),
                    'astrofont' => __('AstroFont glyphs', 'astrology-suite-freeastroapi'),
                ]);
                self::render_vedic_select_control('body_label_mode', __('Graha labels', 'astrology-suite-freeastroapi'), $settings, [
                    'abbr' => __('Abbreviations', 'astrology-suite-freeastroapi'),
                    'glyph' => __('Glyphs', 'astrology-suite-freeastroapi'),
                    'astrofont' => __('AstroFont glyphs', 'astrology-suite-freeastroapi'),
                    'visual' => __('Visual markers', 'astrology-suite-freeastroapi'),
                ]);
                self::render_vedic_number_control('size', __('Chart size per division', 'astrology-suite-freeastroapi'), $settings, 360, 900, __('Pixels used for each D1, D9, or other panel.', 'astrology-suite-freeastroapi'));
                self::render_vedic_checkbox_control('include_outer_planets', __('Include Uranus, Neptune, and Pluto by default', 'astrology-suite-freeastroapi'), $settings);
                ?>
            </section>

            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Visible chart elements', 'astrology-suite-freeastroapi'); ?></h3>
                <?php
                self::render_vedic_checkbox_control('show_titles', __('Show chart titles', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('show_subtitles', __('Show chart subtitles', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('show_division_badge', __('Show D1/D9 division badges', 'astrology-suite-freeastroapi'), $settings);
                ?>
                <p class="description"><?php esc_html_e('House labels (H1–H12) are always hidden and are not offered as a setting.', 'astrology-suite-freeastroapi'); ?></p>
            </section>

            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Custom colors', 'astrology-suite-freeastroapi'); ?></h3>
                <?php self::render_vedic_checkbox_control('custom_colors', __('Override the built-in Vedic theme colors', 'astrology-suite-freeastroapi'), $settings); ?>
                <details class="astrology-suite-vedic-advanced-settings">
                    <summary><?php esc_html_e('Advanced chart colors', 'astrology-suite-freeastroapi'); ?></summary>
                    <div class="astrology-suite-vedic-color-grid">
                        <?php
                        self::render_vedic_color_control('background', __('Canvas', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('panel_background', __('Panel', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('panel_border', __('Panel border', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('grid', __('Grid', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('outer_line', __('Outer line', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('sign_text', __('Sign text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('house_text', __('House text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('body_text', __('Graha text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('ascendant_text', __('Ascendant', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('brand_text', __('Brand text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('title_text', __('Title text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('subtitle_text', __('Subtitle text', 'astrology-suite-freeastroapi'), $settings);
                        self::render_vedic_color_control('badge_background', __('Division badge', 'astrology-suite-freeastroapi'), $settings);
                        ?>
                    </div>
                </details>
            </section>

            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Optional sizing overrides', 'astrology-suite-freeastroapi'); ?></h3>
                <p class="description"><?php esc_html_e('Leave these at 0 to use FreeAstroAPI’s layout-specific defaults.', 'astrology-suite-freeastroapi'); ?></p>
                <details class="astrology-suite-vedic-advanced-settings">
                    <summary><?php esc_html_e('Typography and spacing overrides', 'astrology-suite-freeastroapi'); ?></summary>
                    <?php
                    self::render_vedic_number_control('sign_font_size', __('Sign font size', 'astrology-suite-freeastroapi'), $settings, 0, 64, __('Pixels; 0 uses the API default.', 'astrology-suite-freeastroapi'));
                    self::render_vedic_number_control('body_font_size', __('Graha font size', 'astrology-suite-freeastroapi'), $settings, 0, 64, __('Pixels; 0 uses the API default.', 'astrology-suite-freeastroapi'));
                    self::render_vedic_number_control('grid_line_width', __('Grid line width', 'astrology-suite-freeastroapi'), $settings, 0, 12, __('Pixels; 0 uses the API default.', 'astrology-suite-freeastroapi'));
                    self::render_vedic_number_control('outer_line_width', __('Outer line width', 'astrology-suite-freeastroapi'), $settings, 0, 12, __('Pixels; 0 uses the API default.', 'astrology-suite-freeastroapi'));
                    self::render_vedic_number_control('panel_padding', __('Panel padding', 'astrology-suite-freeastroapi'), $settings, 0, 60, __('Pixels; 0 uses the API default.', 'astrology-suite-freeastroapi'));
                    ?>
                </details>
            </section>
        </div>
        <?php
    }

    public static function render_vedic_workbench_defaults_field(): void
    {
        $settings = self::vedic_chart_settings();
        $selected_divisions = array_map('intval', (array) $settings['default_divisions']);
        self::render_chart_theme_admin_styles();
        ?>
        <div class="astrology-suite-vedic-settings-grid">
            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Starting workbench data', 'astrology-suite-freeastroapi'); ?></h3>
                <fieldset class="astrology-suite-vedic-module-list">
                    <legend><?php esc_html_e('Default divisional charts', 'astrology-suite-freeastroapi'); ?></legend>
                    <input type="hidden" name="<?php echo esc_attr(self::OPTION_VEDIC_CHART_SETTINGS . '[default_divisions][]'); ?>" value="" />
                    <?php foreach (self::vedic_division_options() as $division => $label) : ?>
                        <label class="astrology-suite-vedic-checkbox">
                            <input
                                type="checkbox"
                                name="<?php echo esc_attr(self::OPTION_VEDIC_CHART_SETTINGS . '[default_divisions][]'); ?>"
                                value="<?php echo esc_attr((string) $division); ?>"
                                <?php checked(in_array($division, $selected_divisions, true), true); ?>
                            />
                            <?php echo esc_html($label); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
                <?php
                self::render_vedic_select_control('dasha_levels', __('Dasha depth', 'astrology-suite-freeastroapi'), $settings, [
                    '0' => __('Dasha off', 'astrology-suite-freeastroapi'),
                    '1' => __('Mahadasha', 'astrology-suite-freeastroapi'),
                    '2' => __('Mahadasha + Antardasha', 'astrology-suite-freeastroapi'),
                    '3' => __('Through Pratyantardasha', 'astrology-suite-freeastroapi'),
                ]);
                ?>
            </section>
            <section class="astrology-suite-vedic-settings-card">
                <h3><?php esc_html_e('Default result modules', 'astrology-suite-freeastroapi'); ?></h3>
                <?php
                self::render_vedic_checkbox_control('include_yogas', __('Yogas and doshas', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('include_panchang', __('Birth Panchang', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('include_shadbala', __('Shadbala strength', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('include_ashtakavarga', __('Ashtakavarga', 'astrology-suite-freeastroapi'), $settings);
                self::render_vedic_checkbox_control('include_avastha', __('Baladi Avastha', 'astrology-suite-freeastroapi'), $settings);
                ?>
                <p class="description"><?php esc_html_e('These modules are returned by one Full Vedic Calculate request. Visitor-facing blocks may override them.', 'astrology-suite-freeastroapi'); ?></p>
            </section>
        </div>
        <?php
    }

    private static function vedic_division_options(): array
    {
        $labels = [
            1 => __('D1 - Rashi', 'astrology-suite-freeastroapi'),
            2 => __('D2 - Hora', 'astrology-suite-freeastroapi'),
            3 => __('D3 - Drekkana', 'astrology-suite-freeastroapi'),
            7 => __('D7 - Saptamsha', 'astrology-suite-freeastroapi'),
            9 => __('D9 - Navamsha', 'astrology-suite-freeastroapi'),
            10 => __('D10 - Dashamsha', 'astrology-suite-freeastroapi'),
            12 => __('D12 - Dwadashamsha', 'astrology-suite-freeastroapi'),
            30 => __('D30 - Trimshamsha', 'astrology-suite-freeastroapi'),
            60 => __('D60 - Shashtiamsha', 'astrology-suite-freeastroapi'),
        ];

        return array_intersect_key($labels, array_flip(self::VEDIC_CALCULATION_DIVISIONS));
    }

    private static function render_vedic_select_control(string $key, string $label, array $settings, array $options): void
    {
        $id = self::OPTION_VEDIC_CHART_SETTINGS . '_' . $key;
        echo '<label class="astrology-suite-vedic-control" for="' . esc_attr($id) . '"><span>' . esc_html($label) . '</span>';
        echo '<select id="' . esc_attr($id) . '" name="' . esc_attr(self::OPTION_VEDIC_CHART_SETTINGS . '[' . $key . ']') . '">';
        foreach ($options as $value => $option_label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected((string) $settings[$key], (string) $value, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select></label>';
    }

    private static function render_vedic_checkbox_control(string $key, string $label, array $settings): void
    {
        $name = self::OPTION_VEDIC_CHART_SETTINGS . '[' . $key . ']';
        echo '<input type="hidden" name="' . esc_attr($name) . '" value="0" />';
        echo '<label class="astrology-suite-vedic-checkbox"><input type="checkbox" name="' . esc_attr($name) . '" value="1" ' . checked(!empty($settings[$key]), true, false) . ' /> ' . esc_html($label) . '</label>';
    }

    private static function render_vedic_number_control(string $key, string $label, array $settings, int $min, int $max, string $description): void
    {
        $id = self::OPTION_VEDIC_CHART_SETTINGS . '_' . $key;
        echo '<label class="astrology-suite-vedic-control" for="' . esc_attr($id) . '"><span>' . esc_html($label) . '</span>';
        echo '<input id="' . esc_attr($id) . '" type="number" name="' . esc_attr(self::OPTION_VEDIC_CHART_SETTINGS . '[' . $key . ']') . '" value="' . esc_attr((string) $settings[$key]) . '" min="' . esc_attr((string) $min) . '" max="' . esc_attr((string) $max) . '" step="1" />';
        echo '<small>' . esc_html($description) . '</small></label>';
    }

    private static function render_vedic_color_control(string $key, string $label, array $settings): void
    {
        $id = self::OPTION_VEDIC_CHART_SETTINGS . '_' . $key;
        echo '<label class="astrology-suite-vedic-color" for="' . esc_attr($id) . '"><span>' . esc_html($label) . '</span>';
        echo '<input id="' . esc_attr($id) . '" type="color" name="' . esc_attr(self::OPTION_VEDIC_CHART_SETTINGS . '[' . $key . ']') . '" value="' . esc_attr((string) $settings[$key]) . '" /></label>';
    }

    public static function render_vedic_chart_preview_field(): void
    {
        self::render_chart_theme_admin_styles();
        $preview_url = self::vedic_chart_preview_url();
        $preview_id = 'astrology-suite-vedic-preview-' . wp_unique_id();
        echo '<p><button type="button" class="button button-secondary astrology-suite-vedic-preview-button" data-preview-url="' . esc_url($preview_url) . '" data-preview-target="' . esc_attr($preview_id) . '">' . esc_html__('Generate cached preview', 'astrology-suite-freeastroapi') . '</button></p>';
        echo '<p class="description astrology-suite-vedic-preview-status" aria-live="polite">' . esc_html__('The preview is generated only when requested so it does not spend an API call while you browse settings.', 'astrology-suite-freeastroapi') . '</p>';
        echo '<div class="astrology-suite-vedic-preview" id="' . esc_attr($preview_id) . '" hidden><img alt="' . esc_attr__('Sample D1 and D9 Vedic chart using the saved settings', 'astrology-suite-freeastroapi') . '" /></div>';
        echo '<p class="description">' . wp_kses(
            sprintf(
                /* translators: %s: FreeAstroAPI Vedic visual chart documentation URL. */
                __('The cached sample is generated by the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Vedic visual-chart endpoint</a> after you save your defaults.', 'astrology-suite-freeastroapi'),
                esc_url(self::UPSTREAM_VEDIC_VISUAL_DOCS_URL)
            ),
            ['a' => ['href' => [], 'target' => [], 'rel' => []]]
        ) . '</p>';
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var button = document.querySelector('.astrology-suite-vedic-preview-button');
            if (!button || button.dataset.bound === '1') return;
            button.dataset.bound = '1';
            button.addEventListener('click', function () {
                var container = document.getElementById(button.dataset.previewTarget);
                var status = button.parentElement.nextElementSibling;
                var image = container ? container.querySelector('img') : null;
                if (!container || !image) return;

                button.disabled = true;
                status.textContent = <?php echo wp_json_encode(__('Generating Vedic preview...', 'astrology-suite-freeastroapi')); ?>;
                container.hidden = false;
                fetch(button.dataset.previewUrl, { credentials: 'same-origin' })
                    .then(function (response) {
                        return response.text().then(function (body) {
                            return { ok: response.ok, body: body };
                        });
                    })
                    .then(function (result) {
                        if (!result.ok || result.body.indexOf('Preview unavailable') !== -1 || result.body.indexOf('<svg') === -1) {
                            throw new Error('preview_failed');
                        }
                        var blobUrl = URL.createObjectURL(new Blob([result.body], { type: 'image/svg+xml' }));
                        image.onload = function () {
                            URL.revokeObjectURL(blobUrl);
                            button.disabled = false;
                            status.textContent = <?php echo wp_json_encode(__('Preview generated.', 'astrology-suite-freeastroapi')); ?>;
                        };
                        image.src = blobUrl;
                    })
                    .catch(function () {
                        button.disabled = false;
                        status.textContent = <?php echo wp_json_encode(__('Preview unavailable. Check the API key and try again.', 'astrology-suite-freeastroapi')); ?>;
                    });
            });
        });
        </script>
        <?php
    }

    private static function render_chart_theme_admin_styles(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-chart-theme-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
                gap: 12px;
                max-width: 1120px;
            }
            .astrology-suite-chart-theme-card {
                display: grid;
                gap: 8px;
                min-height: 100%;
                border: 1px solid #dcdcde;
                border-radius: 8px;
                padding: 12px;
                background: #fff;
                cursor: pointer;
                box-sizing: border-box;
            }
            .astrology-suite-chart-theme-card.is-selected {
                border-color: #2271b1;
                box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.16);
            }
            .astrology-suite-chart-theme-card input {
                margin: 0;
            }
            .astrology-suite-chart-theme-card strong {
                font-size: 14px;
            }
            .astrology-suite-chart-theme-card span.description {
                color: #50575e;
                font-size: 12px;
                line-height: 1.4;
            }
            .astrology-suite-chart-preview {
                width: 100%;
                max-width: 180px;
                aspect-ratio: 1;
                border: 1px solid #dcdcde;
                border-radius: 8px;
                overflow: hidden;
                background-color: #f6f7f7;
                background-image: linear-gradient(45deg, #e5e7eb 25%, transparent 25%), linear-gradient(-45deg, #e5e7eb 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e5e7eb 75%), linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
                background-size: 16px 16px;
                background-position: 0 0, 0 8px, 8px -8px, -8px 0;
            }
            .astrology-suite-chart-preview img {
                display: block;
                width: 100%;
                height: auto;
            }
            .astrology-suite-chart-advanced {
                max-width: 980px;
                border: 1px solid #dcdcde;
                border-radius: 8px;
                padding: 12px;
                background: #fff;
            }
            .astrology-suite-chart-advanced summary {
                cursor: pointer;
                font-weight: 600;
            }
            .astrology-suite-chart-advanced textarea {
                margin-top: 10px;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            }
            .astrology-suite-chart-body-list {
                display: grid;
                gap: 10px;
                margin-top: 12px;
            }
            .astrology-suite-chart-body-item {
                border-top: 1px solid #dcdcde;
                padding-top: 10px;
            }
            .astrology-suite-chart-body-item summary {
                font-weight: 600;
            }
            .astrology-suite-vedic-settings-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 14px;
                max-width: 1120px;
            }
            .astrology-suite-vedic-settings-card {
                border: 1px solid #dcdcde;
                border-radius: 8px;
                padding: 14px;
                background: #fff;
            }
            .astrology-suite-vedic-settings-card h3 {
                margin: 0 0 12px;
            }
            .astrology-suite-vedic-control {
                display: grid;
                gap: 5px;
                margin-bottom: 12px;
                font-weight: 600;
            }
            .astrology-suite-vedic-control select,
            .astrology-suite-vedic-control input[type="number"] {
                width: 100%;
                max-width: 320px;
            }
            .astrology-suite-vedic-control small {
                color: #646970;
                font-weight: 400;
            }
            .astrology-suite-vedic-settings-card .astrology-suite-vedic-checkbox {
                display: flex;
                align-items: center;
                gap: 7px;
                margin: 10px 0;
            }
            .astrology-suite-vedic-module-list {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                margin: 0;
                padding: 0;
                border: 0;
            }
            .astrology-suite-vedic-module-list legend {
                grid-column: 1 / -1;
                margin-bottom: 10px;
                font-weight: 600;
            }
            .astrology-suite-vedic-advanced-settings {
                margin-top: 14px;
                padding: 12px;
                border: 1px solid #dcdcde;
                border-radius: 6px;
                background: #f6f7f7;
            }
            .astrology-suite-vedic-advanced-settings > summary {
                cursor: pointer;
                font-weight: 600;
            }
            .astrology-suite-vedic-color-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(110px, 1fr));
                gap: 10px;
                margin-top: 12px;
            }
            .astrology-suite-vedic-color {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                font-weight: 600;
            }
            .astrology-suite-vedic-preview {
                width: min(100%, 920px);
                min-height: 260px;
                border: 1px solid #dcdcde;
                border-radius: 8px;
                overflow: hidden;
                background: #f6f7f7;
            }
            .astrology-suite-vedic-preview img {
                display: block;
                width: 100%;
                height: auto;
            }
            @media (max-width: 782px) {
                .astrology-suite-vedic-settings-grid,
                .astrology-suite-vedic-color-grid,
                .astrology-suite-vedic-module-list {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <?php
    }

    private static function render_chart_theme_card(string $slug, string $label, string $description, bool $selected): void
    {
        $id = self::OPTION_CHART_THEME . '_' . sanitize_key($slug);
        /* translators: %s: chart theme label. */
        $preview_alt = sprintf(__('%s chart preview', 'astrology-suite-freeastroapi'), $label);
        echo '<label class="astrology-suite-chart-theme-card' . ($selected ? ' is-selected' : '') . '" for="' . esc_attr($id) . '">';
        echo '<input id="' . esc_attr($id) . '" type="radio" name="' . esc_attr(self::OPTION_CHART_THEME) . '" value="' . esc_attr($slug) . '" ' . checked($selected, true, false) . ' />';
        echo '<span class="astrology-suite-chart-preview"><img src="' . esc_url(self::chart_preview_url($slug)) . '" alt="' . esc_attr($preview_alt) . '" loading="lazy" /></span>';
        echo '<strong>' . esc_html($label) . '</strong>';
        echo '<span class="description">' . esc_html($description) . '</span>';
        echo '</label>';
    }

    private static function chart_preview_url(string $theme): string
    {
        $theme = self::sanitize_chart_theme($theme);
        return add_query_arg(
            [
                'action' => 'astrology_suite_chart_preview',
                'theme' => $theme,
                'nonce' => wp_create_nonce('astrology_suite_chart_preview_' . $theme),
            ],
            admin_url('admin-ajax.php')
        );
    }

    private static function style_chart_preview_svg_url(): string
    {
        $theme = self::sanitize_chart_theme((string) self::get_option_value(self::OPTION_CHART_THEME, 'current'));
        $body = self::selected_chart_body();
        $hash = md5($theme . '|' . wp_json_encode($body));

        return add_query_arg(
            [
                'action' => 'astrology_suite_style_chart_preview_svg',
                'nonce' => wp_create_nonce('astrology_suite_style_chart_preview_svg_' . $hash),
            ],
            admin_url('admin-ajax.php')
        );
    }

    private static function vedic_chart_preview_url(): string
    {
        $settings = self::vedic_chart_settings();
        $hash = md5((string) wp_json_encode($settings));

        return add_query_arg(
            [
                'action' => 'astrology_suite_vedic_chart_preview_svg',
                'nonce' => wp_create_nonce('astrology_suite_vedic_chart_preview_svg_' . $hash),
            ],
            admin_url('admin-ajax.php')
        );
    }

    public static function render_vedic_chart_preview_svg(): void
    {
        if (!current_user_can('manage_options')) {
            status_header(403);
            exit;
        }

        $settings = self::vedic_chart_settings();
        $hash = md5((string) wp_json_encode($settings));
        if (!check_ajax_referer('astrology_suite_vedic_chart_preview_svg_' . $hash, 'nonce', false)) {
            status_header(403);
            exit;
        }

        $cache_key = 'astrology_suite_vedic_chart_preview_svg_' . $hash;
        $cached = get_transient($cache_key);
        if (is_string($cached) && $cached !== '') {
            self::output_chart_preview_svg($cached);
        }

        $include_outer_planets = !empty($settings['include_outer_planets']);
        $payload = [
            'year' => 1985,
            'month' => 8,
            'day' => 22,
            'hour' => 6,
            'minute' => 45,
            'city' => 'Mumbai',
            'lat' => 19.076,
            'lng' => 72.8777,
            'tz_str' => 'Asia/Kolkata',
            'ayanamsha' => 'lahiri',
            'house_system' => 'whole_sign',
            'node_type' => $settings['node_type'],
            'divisions' => [1, 9],
            'chart_style' => $settings['chart_style'],
            'format' => 'svg',
            'size' => 520,
            'theme_type' => $settings['theme_type'],
            'body_types' => $include_outer_planets
                ? ['ascendant', 'classical_grahas', 'nodes', 'outer_planets']
                : ['ascendant', 'classical_grahas', 'nodes'],
            'display_settings' => self::vedic_chart_display_settings($settings, $include_outer_planets),
            'chart_config' => self::vedic_chart_config_for_api($settings, $settings['sign_label_mode'], $settings['body_label_mode']),
        ];
        $custom_theme = self::vedic_custom_theme_for_api($settings);
        if ($custom_theme !== []) {
            $payload['custom_theme'] = $custom_theme;
        }

        $svg = self::request('POST', '/vedic/visual/chart', $payload, true, true, self::UPSTREAM_V2_BASE_URL, 30, true);
        if (is_wp_error($svg)) {
            self::output_chart_preview_error($svg->get_error_message());
        }

        set_transient($cache_key, $svg, HOUR_IN_SECONDS);
        self::output_chart_preview_svg($svg);
    }

    public static function render_chart_preview_image(): void
    {
        $theme = self::sanitize_chart_theme((string) filter_input(INPUT_GET, 'theme', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if (!current_user_can('manage_options')) {
            status_header(403);
            exit;
        }

        if (!check_ajax_referer('astrology_suite_chart_preview_' . $theme, 'nonce', false)) {
            status_header(403);
            exit;
        }

        $body = self::chart_body_for_theme($theme);
        $cache_key = 'astrology_suite_chart_preview_' . md5($theme . '|' . wp_json_encode($body));
        $cached = get_transient($cache_key);
        if (is_string($cached) && $cached !== '') {
            self::output_chart_preview_png((string) base64_decode($cached, true));
        }

        $png = self::request_chart_preview_png($body, $theme);
        if (is_wp_error($png)) {
            self::output_chart_preview_error($png->get_error_message());
        }

        set_transient($cache_key, base64_encode($png), HOUR_IN_SECONDS);
        self::output_chart_preview_png($png);
    }

    public static function render_style_chart_preview_svg(): void
    {
        if (!current_user_can('manage_options')) {
            status_header(403);
            exit;
        }

        $theme = self::sanitize_chart_theme((string) self::get_option_value(self::OPTION_CHART_THEME, 'current'));
        $body = self::selected_chart_body();
        $hash = md5($theme . '|' . wp_json_encode($body));

        if (!check_ajax_referer('astrology_suite_style_chart_preview_svg_' . $hash, 'nonce', false)) {
            status_header(403);
            exit;
        }

        $cache_key = 'astrology_suite_style_chart_preview_svg_' . $hash;
        $cached = get_transient($cache_key);
        if (is_string($cached) && $cached !== '') {
            self::output_chart_preview_svg($cached);
        }

        $payload = self::chart_preview_payload($body, $theme, 'svg', 520);
        $svg = self::request('POST', '/natal/chart/', $payload, true, true, null, 25, true);
        if (is_wp_error($svg)) {
            self::output_chart_preview_error($svg->get_error_message());
        }

        set_transient($cache_key, $svg, HOUR_IN_SECONDS);
        self::output_chart_preview_svg($svg);
    }

    private static function chart_preview_config(string $theme): array
    {
        $theme = self::sanitize_chart_theme($theme);
        $presets = self::chart_theme_presets();

        if ($theme === 'custom') {
            return self::custom_chart_config() ?: $presets['current']['config'];
        }

        return $presets[$theme]['config'] ?? $presets['current']['config'];
    }

    private static function request_chart_preview_png(array $body, string $theme)
    {
        $api_key = (string) self::get_option_value(self::OPTION_API_KEY, '');
        if ($api_key === '') {
            return new \WP_Error('missing_api_key', __('Set a FreeAstroAPI key to load chart previews.', 'astrology-suite-freeastroapi'));
        }

        $rate_slot = self::acquire_api_rate_slot_for_preview();
        if (is_wp_error($rate_slot)) {
            return $rate_slot;
        }

        $payload = self::chart_preview_payload($body, $theme);
        $response = wp_remote_post(self::UPSTREAM_BASE_URL . '/natal/chart/', [
            'timeout' => 25,
            'redirection' => 0,
            'reject_unsafe_urls' => true,
            'sslverify' => true,
            'limit_response_size' => self::UPSTREAM_MAX_RESPONSE_BYTES,
            'headers' => [
                'Accept' => 'image/png, application/json;q=0.9',
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
                'Idempotency-Key' => wp_generate_uuid4(),
            ],
            'body' => wp_json_encode($payload),
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $body = (string) wp_remote_retrieve_body($response);
        if (strlen($body) > self::UPSTREAM_MAX_RESPONSE_BYTES) {
            return new \WP_Error('preview_response_too_large', __('FreeAstroAPI returned an oversized chart preview.', 'astrology-suite-freeastroapi'));
        }
        if ($status < 200 || $status >= 300) {
            return self::upstream_http_error($response, $status, $body);
        }

        $png = self::extract_png_response($body);
        if ($png === '') {
            return new \WP_Error('preview_invalid_response', __('FreeAstroAPI did not return a PNG preview.', 'astrology-suite-freeastroapi'));
        }

        return $png;
    }

    private static function chart_preview_payload(array $body, string $theme, string $format = 'png', ?int $forced_size = 520): array
    {
        $theme = self::sanitize_chart_theme($theme);
        $payload = [
            'name' => 'Astrology Suite Preview',
            'year' => 1990,
            'month' => 1,
            'day' => 1,
            'hour' => 12,
            'minute' => 0,
            'city' => 'Paris, France',
            'lat' => 48.8566,
            'lng' => 2.3522,
            'tz_str' => 'Europe/Paris',
            'house_system' => self::western_house_system(),
            'zodiac_type' => 'tropical',
            'include_speed' => true,
            'include_dominants' => true,
            'include_features' => self::get_enabled_features(),
            'interpretation' => ['enable' => false],
        ];

        return self::apply_chart_body($payload, $body, $format, $forced_size, self::chart_preview_config($theme));
    }

    private static function extract_png_response(string $body): string
    {
        if (str_starts_with($body, "\x89PNG\r\n\x1a\n")) {
            return $body;
        }

        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return '';
        }

        foreach (['png', 'image', 'data', 'base64'] as $key) {
            if (empty($decoded[$key]) || !is_string($decoded[$key])) {
                continue;
            }

            $candidate = $decoded[$key];
            if (str_starts_with($candidate, 'data:image/png;base64,')) {
                $candidate = substr($candidate, strlen('data:image/png;base64,'));
            }

            $binary = base64_decode($candidate, true);
            if (is_string($binary) && str_starts_with($binary, "\x89PNG\r\n\x1a\n")) {
                return $binary;
            }
        }

        return '';
    }

    private static function output_chart_preview_png(string $png): void
    {
        if ($png === '') {
            self::output_chart_preview_error(__('Chart preview unavailable.', 'astrology-suite-freeastroapi'));
        }

        nocache_headers();
        header('Content-Type: image/png');
        header('X-Content-Type-Options: nosniff');
        header('Content-Length: ' . strlen($png));
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Validated raw PNG bytes must not be escaped.
        echo $png;
        exit;
    }

    private static function output_chart_preview_svg(string $svg): void
    {
        if ($svg === '') {
            self::output_chart_preview_error(__('Chart preview unavailable.', 'astrology-suite-freeastroapi'));
        }

        nocache_headers();
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG was sanitized before this output helper is called.
        echo $svg;
        exit;
    }

    private static function output_chart_preview_error(string $message): void
    {
        $safe_message = esc_html(wp_html_excerpt(wp_strip_all_tags($message), 72, '…'));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 520" role="img"><rect width="520" height="520" fill="#f6f7f7"/><text x="260" y="246" text-anchor="middle" font-family="system-ui, sans-serif" font-size="24" fill="#1d2327">Preview unavailable</text><text x="260" y="284" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" fill="#50575e">' . $safe_message . '</text></svg>';
        nocache_headers();
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG markup contains only an escaped message.
        echo $svg;
        exit;
    }

    private static function render_checkbox_field(string $option, string $label, bool $default = true): void
    {
        $value = (bool) self::get_option_value($option, $default);
        echo '<input type="hidden" name="' . esc_attr($option) . '" value="0" />';
        echo '<label><input type="checkbox" name="' . esc_attr($option) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html($label) . '</label>';
    }

    public static function render_western_house_system_field(): void
    {
        $value = self::western_house_system();
        $systems = [
            'placidus' => __('Placidus', 'astrology-suite-freeastroapi'),
            'whole_sign' => __('Whole Sign', 'astrology-suite-freeastroapi'),
            'equal' => __('Equal', 'astrology-suite-freeastroapi'),
            'koch' => __('Koch', 'astrology-suite-freeastroapi'),
            'regiomontanus' => __('Regiomontanus', 'astrology-suite-freeastroapi'),
            'porphyry' => __('Porphyry', 'astrology-suite-freeastroapi'),
            'campanus' => __('Campanus', 'astrology-suite-freeastroapi'),
        ];

        echo '<select name="' . esc_attr(self::OPTION_WESTERN_HOUSE_SYSTEM) . '">';
        foreach ($systems as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Applied to Western natal reports, chart wheels, previews, focused Rising/Midheaven calculations, and Western bot natal context.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_western_node_type_field(): void
    {
        $value = self::western_node_type();
        echo '<select name="' . esc_attr(self::OPTION_WESTERN_NODE_TYPE) . '">';
        echo '<option value="true" ' . selected($value, 'true', false) . '>' . esc_html__('True Node', 'astrology-suite-freeastroapi') . '</option>';
        echo '<option value="mean" ' . selected($value, 'mean', false) . '>' . esc_html__('Mean Node', 'astrology-suite-freeastroapi') . '</option>';
        echo '<option value="none" ' . selected($value, 'none', false) . '>' . esc_html__('Do not include a lunar node', 'astrology-suite-freeastroapi') . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__('Choose exactly one node variant to prevent duplicate North Node positions and self-aspects.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_obj_asc_field(): void { self::render_checkbox_field(self::OPTION_OBJ_ASC, __('Enable Ascendant (ASC).', 'astrology-suite-freeastroapi')); }
    public static function render_obj_mc_field(): void { self::render_checkbox_field(self::OPTION_OBJ_MC, __('Enable Midheaven (MC).', 'astrology-suite-freeastroapi')); }
    public static function render_obj_chiron_field(): void { self::render_checkbox_field(self::OPTION_OBJ_CHIRON, __('Include Chiron in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_lilith_field(): void { self::render_checkbox_field(self::OPTION_OBJ_LILITH, __('Include Lilith in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }
    public static function render_obj_part_of_fortune_field(): void { self::render_checkbox_field(self::OPTION_OBJ_PART_OF_FORTUNE, __('Include Part of Fortune in calculation, chart, and report.', 'astrology-suite-freeastroapi')); }

    public static function render_style_show_border_field(): void { self::render_checkbox_field(self::OPTION_STYLE_SHOW_BORDER, __('Show border around plugin container.', 'astrology-suite-freeastroapi')); }

    public static function render_style_preview_field(): void
    {
        self::render_style_preview_admin_styles();
        $classes = 'wp-block-astrology-suite-widget astrology-suite-style-preview-widget';
        if (!(bool) self::get_option_value(self::OPTION_STYLE_SHOW_BORDER, true)) {
            $classes .= ' astrology-suite-no-border openastro-no-border';
        }
        if (!(bool) self::get_option_value(self::OPTION_STYLE_SHOW_HISTORY, true)) {
            $classes .= ' astrology-suite-history-hidden';
        }
        ?>
        <div class="astrology-suite-style-preview-shell">
            <p class="description"><?php esc_html_e('Preview starts with the saved settings and updates as you edit the controls below. Save changes to keep the new style.', 'astrology-suite-freeastroapi'); ?></p>
            <div class="<?php echo esc_attr($classes); ?>" style="<?php echo esc_attr(self::style_preview_inline_vars()); ?>">
                <div class="astrology-suite-style-preview-grid">
                    <section class="astrology-suite-style-preview-panel" aria-label="<?php echo esc_attr__('Input form preview', 'astrology-suite-freeastroapi'); ?>">
                        <h3><?php esc_html_e('Input form', 'astrology-suite-freeastroapi'); ?></h3>
                        <div class="openastro-form">
                            <div class="openastro-form-header">
                                <div class="openastro-load-recent-wrap">
                                    <div class="openastro-load-recent-panel">
                                        <label class="openastro-history-label openastro-load-recent-label" for="astrology-suite-style-preview-history"><?php esc_html_e('Past inputs', 'astrology-suite-freeastroapi'); ?></label>
                                        <select id="astrology-suite-style-preview-history" class="openastro-history-select openastro-load-recent-select" aria-label="<?php echo esc_attr__('Load recent past inputs', 'astrology-suite-freeastroapi'); ?>">
                                            <option><?php esc_html_e('Past inputs...', 'astrology-suite-freeastroapi'); ?></option>
                                            <option><?php esc_html_e('Ada · Paris, FR · 1990-01-01', 'astrology-suite-freeastroapi'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <label>
                                <?php esc_html_e('Name', 'astrology-suite-freeastroapi'); ?>
                                <input class="openastro-input" type="text" value="<?php echo esc_attr__('Ada Lovelace', 'astrology-suite-freeastroapi'); ?>" readonly />
                            </label>

                            <div class="astrology-suite-style-preview-row">
                                <label>
                                    <?php esc_html_e('Birth date', 'astrology-suite-freeastroapi'); ?>
                                    <input class="openastro-input" type="text" value="1990-01-01" readonly />
                                </label>
                                <label>
                                    <?php esc_html_e('Birth time', 'astrology-suite-freeastroapi'); ?>
                                    <input class="openastro-input" type="text" value="12:00" readonly />
                                </label>
                            </div>

                            <label class="openastro-city-field is-open">
                                <?php esc_html_e('City', 'astrology-suite-freeastroapi'); ?>
                                <input class="openastro-input openastro-city-input" type="text" value="<?php echo esc_attr__('Paris', 'astrology-suite-freeastroapi'); ?>" readonly />
                                <span class="openastro-city-loading"><?php esc_html_e('2 suggestions', 'astrology-suite-freeastroapi'); ?></span>
                                <div class="openastro-results">
                                    <?php self::render_style_preview_city_result(__('Paris', 'astrology-suite-freeastroapi'), 'FR', 'Europe/Paris', '48.8566, 2.3522', true); ?>
                                    <?php self::render_style_preview_city_result(__('Paris', 'astrology-suite-freeastroapi'), 'US', 'America/Chicago', '33.6609, -95.5555', false); ?>
                                </div>
                            </label>

                            <label class="openastro-inline">
                                <input type="checkbox" checked disabled />
                                <?php esc_html_e('Include minor aspects', 'astrology-suite-freeastroapi'); ?>
                            </label>

                            <button class="openastro-btn" type="button"><?php esc_html_e('Generate', 'astrology-suite-freeastroapi'); ?></button>
                            <p class="openastro-status"><?php esc_html_e('Ready to generate.', 'astrology-suite-freeastroapi'); ?></p>
                        </div>
                    </section>

                    <section class="astrology-suite-style-preview-panel" aria-label="<?php echo esc_attr__('Result preview', 'astrology-suite-freeastroapi'); ?>">
                        <h3><?php esc_html_e('Results', 'astrology-suite-freeastroapi'); ?></h3>
                        <div class="openastro-output">
                            <h2 class="openastro-report-title"><?php esc_html_e('Natal Report', 'astrology-suite-freeastroapi'); ?></h2>
                            <section class="openastro-card openastro-chart-card">
                                <h3><?php esc_html_e('Natal Wheel', 'astrology-suite-freeastroapi'); ?></h3>
                                <button class="openastro-print-btn" type="button"><?php esc_html_e('Print', 'astrology-suite-freeastroapi'); ?></button>
                                <div class="openastro-chart" aria-hidden="true">
                                    <img class="astrology-suite-style-preview-chart-img" src="<?php echo esc_url(self::style_chart_preview_svg_url()); ?>" alt="<?php echo esc_attr__('Sample natal wheel using the selected chart theme', 'astrology-suite-freeastroapi'); ?>" loading="lazy" />
                                </div>
                            </section>
                            <section class="openastro-card">
                                <h3><?php esc_html_e('Planet highlights', 'astrology-suite-freeastroapi'); ?></h3>
                                <div class="openastro-planet-item">
                                    <h4><span class="openastro-astro-glyph">s</span><?php esc_html_e('Sun in Capricorn', 'astrology-suite-freeastroapi'); ?></h4>
                                    <p><?php esc_html_e('A concise interpretation paragraph previews body text, labels, spacing, and card contrast.', 'astrology-suite-freeastroapi'); ?></p>
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Body', 'astrology-suite-freeastroapi'); ?></th>
                                            <th><?php esc_html_e('Sign', 'astrology-suite-freeastroapi'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php esc_html_e('Moon', 'astrology-suite-freeastroapi'); ?></td>
                                            <td><?php esc_html_e('Libra', 'astrology-suite-freeastroapi'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                            <section class="openastro-card openastro-simple-sign-card">
                                <h3><?php esc_html_e('Moon Sign', 'astrology-suite-freeastroapi'); ?></h3>
                                <p class="openastro-simple-sign-value"><span class="openastro-astro-glyph">X</span><?php esc_html_e('Libra', 'astrology-suite-freeastroapi'); ?></p>
                                <div class="openastro-simple-sign-interpretation">
                                    <h4><?php esc_html_e('Interpretation', 'astrology-suite-freeastroapi'); ?></h4>
                                    <p><?php esc_html_e('This card previews simple-sign result spacing and typography.', 'astrology-suite-freeastroapi'); ?></p>
                                </div>
                            </section>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <?php
    }

    private static function render_style_preview_city_result(string $name, string $country, string $timezone, string $coords, bool $active): void
    {
        ?>
        <button class="openastro-result-btn<?php echo $active ? ' is-active' : ''; ?>" type="button">
            <span class="openastro-result-main">
                <span class="openastro-result-primary">
                    <span class="openastro-result-name"><?php echo esc_html($name); ?></span>
                    <span class="openastro-result-country"><?php echo esc_html($country); ?></span>
                </span>
            </span>
            <span class="openastro-result-meta">
                <span class="openastro-result-meta-item"><?php echo esc_html($timezone); ?></span>
                <span class="openastro-result-meta-item"><?php echo esc_html($coords); ?></span>
            </span>
        </button>
        <?php
    }

    private static function style_preview_inline_vars(): string
    {
        $background = (bool) self::get_option_value(self::OPTION_STYLE_TRANSPARENT_BG, false)
            ? 'transparent'
            : (string) self::get_option_value(self::OPTION_STYLE_BG_COLOR, '#ffffff');

        $vars = [
            '--openastro-bg' => $background,
            '--openastro-text' => (string) self::get_option_value(self::OPTION_STYLE_TEXT_COLOR, '#111111'),
            '--openastro-history-bg' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BG_COLOR, '#f9fafb'),
            '--openastro-history-border' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BORDER_COLOR, '#e5e7eb'),
            '--openastro-history-text' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_TEXT_COLOR, '#111111'),
            '--openastro-input-radius' => (int) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_RADIUS, 6) . 'px',
            '--openastro-input-border-color' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_COLOR, '#d1d5db'),
            '--openastro-input-bg' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BG_COLOR, '#ffffff'),
            '--openastro-input-text' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_TEXT_COLOR, '#111111'),
            '--openastro-input-placeholder' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, '#6b7280'),
            '--openastro-button-radius' => (int) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, 6) . 'px',
            '--openastro-button-bg' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BG_COLOR, '#111827'),
            '--openastro-button-text' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_TEXT_COLOR, '#ffffff'),
            '--openastro-button-border' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_COLOR, '#111827'),
            '--openastro-label-color' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_COLOR, '#111111'),
            '--openastro-label-weight' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_WEIGHT, '600'),
            '--openastro-focus-ring' => (string) self::get_option_value(self::OPTION_STYLE_FOCUS_RING_COLOR, '#2563eb'),
            '--openastro-astro-font-size' => (int) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_SIZE, 20) . 'px',
            '--openastro-astro-font-color' => (string) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_COLOR, '#111111'),
            '--openastro-font-h2' => (int) self::get_option_value(self::OPTION_TYPO_H2, 28) . 'px',
            '--openastro-font-h3' => (int) self::get_option_value(self::OPTION_TYPO_H3, 22) . 'px',
            '--openastro-font-h4' => (int) self::get_option_value(self::OPTION_TYPO_H4, 18) . 'px',
            '--openastro-font-body' => (int) self::get_option_value(self::OPTION_TYPO_BODY, 16) . 'px',
            '--openastro-font-table' => (int) self::get_option_value(self::OPTION_TYPO_TABLE, 14) . 'px',
            '--openastro-font-input' => (int) self::get_option_value(self::OPTION_TYPO_INPUT, 16) . 'px',
            '--openastro-font-button' => (int) self::get_option_value(self::OPTION_TYPO_BUTTON, 16) . 'px',
            '--openastro-font-meta' => (int) self::get_option_value(self::OPTION_TYPO_META, 13) . 'px',
        ];

        $style = [];
        foreach ($vars as $key => $value) {
            $style[] = $key . ': ' . $value;
        }

        return implode('; ', $style);
    }

    private static function render_style_preview_admin_styles(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-style-preview-shell {
                max-width: 1120px;
            }
            .astrology-suite-style-preview-shell > .description {
                margin: 0 0 10px;
            }
            .astrology-suite-style-preview-widget {
                max-width: 1120px;
            }
            .astrology-suite-style-preview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 18px;
                align-items: start;
            }
            .astrology-suite-style-preview-panel {
                min-width: 0;
            }
            .astrology-suite-style-preview-panel > h3 {
                margin: 0 0 12px;
                font-size: 14px;
                line-height: 1.35;
                text-transform: uppercase;
                letter-spacing: 0.02em;
                color: #50575e;
            }
            .astrology-suite-style-preview-widget .openastro-form {
                max-width: none;
                margin: 0;
            }
            .astrology-suite-style-preview-widget.astrology-suite-history-hidden .openastro-form-header {
                display: none;
            }
            .astrology-suite-style-preview-widget .openastro-output {
                gap: 14px;
            }
            .astrology-suite-style-preview-row {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }
            .astrology-suite-style-preview-widget .openastro-chart svg,
            .astrology-suite-style-preview-chart-img {
                display: block;
                width: 100%;
                height: auto;
                max-width: 280px;
                margin: 0 auto;
            }
            .astrology-suite-style-preview-widget table {
                margin-top: 10px;
            }
            @media (max-width: 680px) {
                .astrology-suite-style-preview-row {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <script>
            (function () {
                function initAstrologySuiteStylePreview() {
                    var preview = document.querySelector('.astrology-suite-style-preview-widget');
                    if (!preview) {
                        return;
                    }

                    var fields = {
                        <?php echo wp_json_encode(self::OPTION_STYLE_TEXT_COLOR); ?>: ['--openastro-text', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_HISTORY_BG_COLOR); ?>: ['--openastro-history-bg', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_HISTORY_BORDER_COLOR); ?>: ['--openastro-history-border', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_HISTORY_TEXT_COLOR); ?>: ['--openastro-history-text', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_INPUT_BORDER_RADIUS); ?>: ['--openastro-input-radius', 'px'],
                        <?php echo wp_json_encode(self::OPTION_STYLE_INPUT_BORDER_COLOR); ?>: ['--openastro-input-border-color', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_INPUT_BG_COLOR); ?>: ['--openastro-input-bg', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_INPUT_TEXT_COLOR); ?>: ['--openastro-input-text', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR); ?>: ['--openastro-input-placeholder', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_BUTTON_BORDER_RADIUS); ?>: ['--openastro-button-radius', 'px'],
                        <?php echo wp_json_encode(self::OPTION_STYLE_BUTTON_BG_COLOR); ?>: ['--openastro-button-bg', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_BUTTON_TEXT_COLOR); ?>: ['--openastro-button-text', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_BUTTON_BORDER_COLOR); ?>: ['--openastro-button-border', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_LABEL_COLOR); ?>: ['--openastro-label-color', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_LABEL_WEIGHT); ?>: ['--openastro-label-weight', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_FOCUS_RING_COLOR); ?>: ['--openastro-focus-ring', ''],
                        <?php echo wp_json_encode(self::OPTION_STYLE_ASTRO_FONT_SIZE); ?>: ['--openastro-astro-font-size', 'px'],
                        <?php echo wp_json_encode(self::OPTION_STYLE_ASTRO_FONT_COLOR); ?>: ['--openastro-astro-font-color', '']
                    };

                    function setPreviewVariable(name, value) {
                        preview.style.setProperty(name, value);
                        if (name.indexOf('--openastro-') === 0) {
                            preview.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
                        }
                    }

                    function updateField(name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        var target = fields[name];
                        if (!field || !target) {
                            return;
                        }
                        var value = field.value;
                        if (value === '') {
                            return;
                        }
                        setPreviewVariable(target[0], value + target[1]);
                    }

                    Object.keys(fields).forEach(function (name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        if (!field) {
                            return;
                        }
                        field.addEventListener('input', function () { updateField(name); });
                        field.addEventListener('change', function () { updateField(name); });
                    });

                    var backgroundColor = document.querySelector('[name="<?php echo esc_js(self::OPTION_STYLE_BG_COLOR); ?>"]');
                    var transparentBackground = document.querySelector('[name="<?php echo esc_js(self::OPTION_STYLE_TRANSPARENT_BG); ?>"][type="checkbox"]');
                    function syncBackground() {
                        var value = backgroundColor && backgroundColor.value ? backgroundColor.value : '#ffffff';
                        setPreviewVariable('--openastro-bg', transparentBackground && transparentBackground.checked ? 'transparent' : value);
                    }
                    if (backgroundColor) {
                        backgroundColor.addEventListener('input', syncBackground);
                        backgroundColor.addEventListener('change', syncBackground);
                    }
                    if (transparentBackground) {
                        transparentBackground.addEventListener('change', syncBackground);
                    }
                    syncBackground();

                    var border = document.querySelector('[name="<?php echo esc_js(self::OPTION_STYLE_SHOW_BORDER); ?>"][type="checkbox"]');
                    if (border) {
                        border.addEventListener('change', function () {
                            preview.classList.toggle('astrology-suite-no-border', !border.checked);
                            preview.classList.toggle('openastro-no-border', !border.checked);
                        });
                    }

                    var history = document.querySelector('[name="<?php echo esc_js(self::OPTION_STYLE_SHOW_HISTORY); ?>"][type="checkbox"]');
                    if (history) {
                        history.addEventListener('change', function () {
                            preview.classList.toggle('astrology-suite-history-hidden', !history.checked);
                        });
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAstrologySuiteStylePreview);
                } else {
                    initAstrologySuiteStylePreview();
                }
            })();
        </script>
        <?php
    }

    public static function render_style_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BG_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
        echo '<p class="description">' . esc_html__('Used when the plugin background is enabled.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_style_transparent_bg_field(): void
    {
        self::render_checkbox_field(self::OPTION_STYLE_TRANSPARENT_BG, __('Use a transparent plugin background.', 'astrology-suite-freeastroapi'), false);
        echo '<p class="description">' . esc_html__('Keeps the saved background color available for when this option is turned off.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_style_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_TEXT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_group_history(): void
    {
        echo '<p><strong>' . esc_html__('Past inputs dropdown', 'astrology-suite-freeastroapi') . '</strong></p>';
        echo '<p class="description">' . esc_html__('Controls the saved local-browser input selector shown above each widget form.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_style_show_history_field(): void
    {
        self::render_checkbox_field(self::OPTION_STYLE_SHOW_HISTORY, __('Show the Past inputs dropdown above widget forms.', 'astrology-suite-freeastroapi'));
        echo '<p class="description">' . esc_html__('When hidden, new widget submissions are not added to the Past inputs list.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_style_history_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BG_COLOR, '#f9fafb');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_HISTORY_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_history_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BORDER_COLOR, '#e5e7eb');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_HISTORY_BORDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_history_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_HISTORY_TEXT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_HISTORY_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
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

    public static function render_style_group_modal(): void
    {
        echo '<p><strong>' . esc_html__('Aspect interpretation modal', 'astrology-suite-freeastroapi') . '</strong></p>';
        echo '<p class="description">' . esc_html__('Controls the small modal opened when a visitor clicks an aspect link inside a planet interpretation.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_style_input_border_radius_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_RADIUS, 6);
        echo '<input type="number" min="0" max="40" step="1" name="' . esc_attr(self::OPTION_STYLE_INPUT_BORDER_RADIUS) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_style_input_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_COLOR, '#d1d5db');
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
        $value = (string) self::get_option_value(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, '#6b7280');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_border_radius_field(): void
    {
        $value = (int) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, 6);
        echo '<input type="number" min="0" max="40" step="1" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BORDER_RADIUS) . '" value="' . esc_attr((string) $value) . '" /> px';
    }

    public static function render_style_button_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BG_COLOR, '#111827');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_TEXT_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_button_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_COLOR, '#111827');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_BUTTON_BORDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_label_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_LABEL_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_LABEL_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_label_weight_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_LABEL_WEIGHT, '600');
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
        $value = (string) self::get_option_value(self::OPTION_STYLE_FOCUS_RING_COLOR, '#2563eb');
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

    public static function render_style_modal_bg_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_MODAL_BG_COLOR, '#ffffff');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_MODAL_BG_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_modal_text_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_MODAL_TEXT_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_MODAL_TEXT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_modal_border_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_MODAL_BORDER_COLOR, '#111111');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_MODAL_BORDER_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_style_modal_accent_color_field(): void
    {
        $value = (string) self::get_option_value(self::OPTION_STYLE_MODAL_ACCENT_COLOR, '#d97706');
        echo '<input type="color" name="' . esc_attr(self::OPTION_STYLE_MODAL_ACCENT_COLOR) . '" value="' . esc_attr($value) . '" />';
    }

    private static function vedic_style_defaults(): array
    {
        return [
            self::OPTION_VEDIC_SURFACE_BG_COLOR => '#fffdf9',
            self::OPTION_VEDIC_SURFACE_TEXT_COLOR => '#29211c',
            self::OPTION_VEDIC_SURFACE_BORDER_COLOR => '#e7ddd2',
            self::OPTION_VEDIC_ACCENT_COLOR => '#ac3b32',
            self::OPTION_VEDIC_TILE_BG_COLOR => '#f8f2ea',
            self::OPTION_VEDIC_TILE_TEXT_COLOR => '#29211c',
            self::OPTION_VEDIC_TILE_BORDER_COLOR => '#e5d6c6',
            self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR => '#efe3d5',
            self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR => '#4b3025',
            self::OPTION_VEDIC_BADGE_BG_COLOR => '#f9e2dd',
            self::OPTION_VEDIC_BADGE_TEXT_COLOR => '#8f251d',
        ];
    }

    private static function vedic_style_variable_map(): array
    {
        return [
            self::OPTION_VEDIC_SURFACE_BG_COLOR => '--openastro-vedic-surface-bg',
            self::OPTION_VEDIC_SURFACE_TEXT_COLOR => '--openastro-vedic-surface-text',
            self::OPTION_VEDIC_SURFACE_BORDER_COLOR => '--openastro-vedic-surface-border',
            self::OPTION_VEDIC_ACCENT_COLOR => '--openastro-vedic-accent',
            self::OPTION_VEDIC_TILE_BG_COLOR => '--openastro-vedic-tile-bg',
            self::OPTION_VEDIC_TILE_TEXT_COLOR => '--openastro-vedic-tile-text',
            self::OPTION_VEDIC_TILE_BORDER_COLOR => '--openastro-vedic-tile-border',
            self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR => '--openastro-vedic-table-header-bg',
            self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR => '--openastro-vedic-table-header-text',
            self::OPTION_VEDIC_BADGE_BG_COLOR => '--openastro-vedic-badge-bg',
            self::OPTION_VEDIC_BADGE_TEXT_COLOR => '--openastro-vedic-badge-text',
        ];
    }

    private static function vedic_style_inline_vars(): string
    {
        $defaults = self::vedic_style_defaults();
        $vars = [];
        foreach (self::vedic_style_variable_map() as $option => $var_name) {
            $value = (string) self::get_option_value($option, $defaults[$option]);
            $vars[$var_name] = $value;
            $vars[str_replace('--openastro-', '--astrology-suite-', $var_name)] = $value;
        }

        $style = [];
        foreach ($vars as $key => $value) {
            $style[] = $key . ': ' . $value;
        }

        return implode('; ', $style);
    }

    public static function render_vedic_style_preview_field(): void
    {
        self::render_vedic_style_preview_admin_assets();
        ?>
        <div class="wp-block-astrology-suite-widget astrology-suite-vedic-style-preview openastro-vedic" style="<?php echo esc_attr(self::vedic_style_inline_vars()); ?>">
            <section class="openastro-vedic-report">
                <div class="openastro-vedic-heading">
                    <div>
                        <span class="openastro-vedic-eyebrow"><?php esc_html_e('Vedic Workbench', 'astrology-suite-freeastroapi'); ?></span>
                        <h3><?php esc_html_e('Sidereal chart overview', 'astrology-suite-freeastroapi'); ?></h3>
                    </div>
                    <span class="openastro-vedic-badge"><?php esc_html_e('Lahiri', 'astrology-suite-freeastroapi'); ?></span>
                </div>
                <div class="openastro-vedic-summary-grid">
                    <article><span><?php esc_html_e('Lagna', 'astrology-suite-freeastroapi'); ?></span><strong><?php esc_html_e('Scorpio', 'astrology-suite-freeastroapi'); ?></strong><small><?php esc_html_e('Anuradha', 'astrology-suite-freeastroapi'); ?></small></article>
                    <article><span><?php esc_html_e('Chandra', 'astrology-suite-freeastroapi'); ?></span><strong><?php esc_html_e('Taurus', 'astrology-suite-freeastroapi'); ?></strong><small><?php esc_html_e('Rohini', 'astrology-suite-freeastroapi'); ?></small></article>
                    <article><span><?php esc_html_e('Surya', 'astrology-suite-freeastroapi'); ?></span><strong><?php esc_html_e('Leo', 'astrology-suite-freeastroapi'); ?></strong><small><?php esc_html_e('Magha', 'astrology-suite-freeastroapi'); ?></small></article>
                    <article><span><?php esc_html_e('Active dasha', 'astrology-suite-freeastroapi'); ?></span><strong><?php esc_html_e('Venus', 'astrology-suite-freeastroapi'); ?></strong><small><?php esc_html_e('Until 2031', 'astrology-suite-freeastroapi'); ?></small></article>
                </div>
                <details class="openastro-vedic-section" open>
                    <summary><?php esc_html_e('Graha positions', 'astrology-suite-freeastroapi'); ?><span><?php esc_html_e('D1 · 3 shown', 'astrology-suite-freeastroapi'); ?></span></summary>
                    <div class="openastro-vedic-table-wrap">
                        <table>
                            <thead><tr><th><?php esc_html_e('Graha', 'astrology-suite-freeastroapi'); ?></th><th><?php esc_html_e('Rashi', 'astrology-suite-freeastroapi'); ?></th><th><?php esc_html_e('Nakshatra', 'astrology-suite-freeastroapi'); ?></th><th><?php esc_html_e('Status', 'astrology-suite-freeastroapi'); ?></th></tr></thead>
                            <tbody>
                                <tr><td><?php esc_html_e('Sun', 'astrology-suite-freeastroapi'); ?></td><td><?php esc_html_e('Leo 12°', 'astrology-suite-freeastroapi'); ?></td><td><?php esc_html_e('Magha', 'astrology-suite-freeastroapi'); ?></td><td><span class="openastro-vedic-strength is-sufficient"><?php esc_html_e('Strong', 'astrology-suite-freeastroapi'); ?></span></td></tr>
                                <tr><td><?php esc_html_e('Moon', 'astrology-suite-freeastroapi'); ?></td><td><?php esc_html_e('Taurus 18°', 'astrology-suite-freeastroapi'); ?></td><td><?php esc_html_e('Rohini', 'astrology-suite-freeastroapi'); ?></td><td><span class="openastro-vedic-strength"><?php esc_html_e('Steady', 'astrology-suite-freeastroapi'); ?></span></td></tr>
                            </tbody>
                        </table>
                    </div>
                </details>
                <details class="openastro-vedic-section" open>
                    <summary><?php esc_html_e('Yogas', 'astrology-suite-freeastroapi'); ?><span><?php esc_html_e('1 highlighted', 'astrology-suite-freeastroapi'); ?></span></summary>
                    <div class="openastro-vedic-yoga-grid">
                        <article><div><span><?php esc_html_e('Raja Yoga', 'astrology-suite-freeastroapi'); ?></span><em><?php esc_html_e('High confidence', 'astrology-suite-freeastroapi'); ?></em></div><h4><?php esc_html_e('Purpose and leadership', 'astrology-suite-freeastroapi'); ?></h4><p><?php esc_html_e('Interpretation and practitioner evidence use the selected surface, tile, and accent palette.', 'astrology-suite-freeastroapi'); ?></p></article>
                    </div>
                </details>
                <p class="openastro-vedic-disclaimer"><?php esc_html_e('Preview data is illustrative. These colors are applied to live Vedic results after saving.', 'astrology-suite-freeastroapi'); ?></p>
            </section>
        </div>
        <p class="description"><?php esc_html_e('The preview updates instantly as you edit the Vedic controls below and does not call FreeAstroAPI.', 'astrology-suite-freeastroapi'); ?></p>
        <?php
    }

    public static function render_vedic_surface_bg_color_field(): void { self::render_color_field(self::OPTION_VEDIC_SURFACE_BG_COLOR, '#fffdf9'); }
    public static function render_vedic_surface_text_color_field(): void { self::render_color_field(self::OPTION_VEDIC_SURFACE_TEXT_COLOR, '#29211c'); }
    public static function render_vedic_surface_border_color_field(): void { self::render_color_field(self::OPTION_VEDIC_SURFACE_BORDER_COLOR, '#e7ddd2'); }
    public static function render_vedic_accent_color_field(): void { self::render_color_field(self::OPTION_VEDIC_ACCENT_COLOR, '#ac3b32'); }
    public static function render_vedic_tile_bg_color_field(): void { self::render_color_field(self::OPTION_VEDIC_TILE_BG_COLOR, '#f8f2ea'); }
    public static function render_vedic_tile_text_color_field(): void { self::render_color_field(self::OPTION_VEDIC_TILE_TEXT_COLOR, '#29211c'); }
    public static function render_vedic_tile_border_color_field(): void { self::render_color_field(self::OPTION_VEDIC_TILE_BORDER_COLOR, '#e5d6c6'); }
    public static function render_vedic_table_header_bg_color_field(): void { self::render_color_field(self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR, '#efe3d5'); }
    public static function render_vedic_table_header_text_color_field(): void { self::render_color_field(self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR, '#4b3025'); }
    public static function render_vedic_badge_bg_color_field(): void { self::render_color_field(self::OPTION_VEDIC_BADGE_BG_COLOR, '#f9e2dd'); }
    public static function render_vedic_badge_text_color_field(): void { self::render_color_field(self::OPTION_VEDIC_BADGE_TEXT_COLOR, '#8f251d'); }

    private static function render_vedic_style_preview_admin_assets(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-vedic-style-preview {
                max-width: 920px;
            }
            .astrology-suite-vedic-style-preview .openastro-vedic-report {
                width: 100%;
            }
            .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap table {
                min-width: 560px;
                table-layout: fixed;
                border: 1px solid var(--astrology-suite-vedic-surface-border, var(--openastro-vedic-surface-border, #e7ddd2));
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 8px;
                overflow: hidden;
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap th,
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap td {
                display: table-cell;
                width: auto;
                margin: 0;
                padding: 10px 12px;
                border: 0;
                vertical-align: middle;
                line-height: 1.35;
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap th {
                font-size: 12px;
                letter-spacing: 0.02em;
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap td {
                font-size: 13px;
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap thead th {
                border-bottom: 1px solid var(--astrology-suite-vedic-surface-border, var(--openastro-vedic-surface-border, #e7ddd2));
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap tbody tr + tr td {
                border-top: 1px solid var(--astrology-suite-vedic-surface-border, var(--openastro-vedic-surface-border, #e7ddd2));
            }
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap th + th,
            .form-table .astrology-suite-vedic-style-preview .openastro-vedic-table-wrap td + td {
                border-left: 1px solid var(--astrology-suite-vedic-surface-border, var(--openastro-vedic-surface-border, #e7ddd2));
            }
            .astrology-suite-vedic-style-preview .openastro-vedic-yoga-grid {
                grid-template-columns: 1fr;
            }
        </style>
        <script>
            (function () {
                function initAstrologySuiteVedicStylePreview() {
                    var preview = document.querySelector('.astrology-suite-vedic-style-preview');
                    if (!preview) {
                        return;
                    }
                    var fields = <?php echo wp_json_encode(self::vedic_style_variable_map()); ?>;
                    function setPreviewVariable(name, value) {
                        preview.style.setProperty(name, value);
                        if (name.indexOf('--openastro-') === 0) {
                            preview.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
                        }
                    }
                    Object.keys(fields).forEach(function (name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        if (!field) {
                            return;
                        }
                        var update = function () {
                            if (field.value) {
                                setPreviewVariable(fields[name], field.value);
                            }
                        };
                        field.addEventListener('input', update);
                        field.addEventListener('change', update);
                    });
                }
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAstrologySuiteVedicStylePreview);
                } else {
                    initAstrologySuiteVedicStylePreview();
                }
            })();
        </script>
        <?php
    }

    public static function render_style_group_horoscope(): void
    {
        echo '<p><strong>' . esc_html__('Horoscope display and styling', 'astrology-suite-freeastroapi') . '</strong></p>';
        echo '<p class="description">' . esc_html__('Controls which parts of the Daily Sign and Personalized Horoscope results are shown and how their cards, chips, tiles, and explanation accents look.', 'astrology-suite-freeastroapi') . '</p>';
    }

    private static function horoscope_display_defaults(): array
    {
        return [
            self::OPTION_HOROSCOPE_SHOW_THEME => true,
            self::OPTION_HOROSCOPE_SHOW_SUMMARY => true,
            self::OPTION_HOROSCOPE_SHOW_SCORES => true,
            self::OPTION_HOROSCOPE_SHOW_SCORE_FACTORS => true,
            self::OPTION_HOROSCOPE_SHOW_PERSONAL_CONTEXT => true,
            self::OPTION_HOROSCOPE_SHOW_LUCKY => true,
            self::OPTION_HOROSCOPE_SHOW_GUIDANCE => true,
            self::OPTION_HOROSCOPE_SHOW_ASTRO => true,
        ];
    }

    private static function horoscope_display_labels(): array
    {
        return [
            self::OPTION_HOROSCOPE_SHOW_THEME => __('Theme and keywords', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_SUMMARY => __('Main interpretation text', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_SCORES => __('Score grid', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_SCORE_FACTORS => __('Expandable score explanations', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_PERSONAL_CONTEXT => __('Personal transits and timing', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_LUCKY => __('Lucky attributes', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_GUIDANCE => __('Do / avoid / supporting insights', 'astrology-suite-freeastroapi'),
            self::OPTION_HOROSCOPE_SHOW_ASTRO => __('Moon and sky highlights', 'astrology-suite-freeastroapi'),
        ];
    }

    private static function horoscope_style_defaults(): array
    {
        return [
            self::OPTION_HOROSCOPE_CARD_BG_COLOR => '#ffffff',
            self::OPTION_HOROSCOPE_CARD_TEXT_COLOR => '#111111',
            self::OPTION_HOROSCOPE_CARD_BORDER_COLOR => '#e5e7eb',
            self::OPTION_HOROSCOPE_THEME_BG_COLOR => '#111827',
            self::OPTION_HOROSCOPE_THEME_TEXT_COLOR => '#ffffff',
            self::OPTION_HOROSCOPE_CHIP_BG_COLOR => '#f9fafb',
            self::OPTION_HOROSCOPE_CHIP_TEXT_COLOR => '#111111',
            self::OPTION_HOROSCOPE_CHIP_BORDER_COLOR => '#e5e7eb',
            self::OPTION_HOROSCOPE_TILE_BG_COLOR => '#f9fafb',
            self::OPTION_HOROSCOPE_TILE_TEXT_COLOR => '#111111',
            self::OPTION_HOROSCOPE_TILE_BORDER_COLOR => '#e5e7eb',
            self::OPTION_HOROSCOPE_ACCENT_COLOR => '#2563eb',
        ];
    }

    private static function horoscope_style_variable_map(): array
    {
        return [
            self::OPTION_HOROSCOPE_CARD_BG_COLOR => '--openastro-horoscope-card-bg',
            self::OPTION_HOROSCOPE_CARD_TEXT_COLOR => '--openastro-horoscope-card-text',
            self::OPTION_HOROSCOPE_CARD_BORDER_COLOR => '--openastro-horoscope-card-border',
            self::OPTION_HOROSCOPE_THEME_BG_COLOR => '--openastro-horoscope-theme-bg',
            self::OPTION_HOROSCOPE_THEME_TEXT_COLOR => '--openastro-horoscope-theme-text',
            self::OPTION_HOROSCOPE_CHIP_BG_COLOR => '--openastro-horoscope-chip-bg',
            self::OPTION_HOROSCOPE_CHIP_TEXT_COLOR => '--openastro-horoscope-chip-text',
            self::OPTION_HOROSCOPE_CHIP_BORDER_COLOR => '--openastro-horoscope-chip-border',
            self::OPTION_HOROSCOPE_TILE_BG_COLOR => '--openastro-horoscope-tile-bg',
            self::OPTION_HOROSCOPE_TILE_TEXT_COLOR => '--openastro-horoscope-tile-text',
            self::OPTION_HOROSCOPE_TILE_BORDER_COLOR => '--openastro-horoscope-tile-border',
            self::OPTION_HOROSCOPE_ACCENT_COLOR => '--openastro-horoscope-accent',
        ];
    }

    private static function horoscope_style_inline_vars(): string
    {
        $defaults = self::horoscope_style_defaults();
        $vars = [];
        foreach (self::horoscope_style_variable_map() as $option => $var_name) {
            $value = (string) self::get_option_value($option, $defaults[$option]);
            $vars[$var_name] = $value;
            $vars[str_replace('--openastro-', '--astrology-suite-', $var_name)] = $value;
        }

        $style = [];
        foreach ($vars as $key => $value) {
            $style[] = $key . ': ' . $value;
        }

        return implode('; ', $style);
    }

    private static function bot_style_defaults(): array
    {
        return [
            self::OPTION_BOT_SHELL_BG_COLOR => '#101014',
            self::OPTION_BOT_HEADER_BG_COLOR => '#17231f',
            self::OPTION_BOT_SCREEN_BG_COLOR => '#0f1f1b',
            self::OPTION_BOT_ACCENT_COLOR => '#facc15',
            self::OPTION_BOT_USER_BUBBLE_BG_COLOR => '#d8f7c5',
            self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR => '#142117',
            self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR => '#202c28',
            self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR => '#f1f5f9',
        ];
    }

    private static function bot_style_variable_map(): array
    {
        return [
            self::OPTION_BOT_SHELL_BG_COLOR => '--openastro-bot-shell-bg',
            self::OPTION_BOT_HEADER_BG_COLOR => '--openastro-bot-header-bg',
            self::OPTION_BOT_SCREEN_BG_COLOR => '--openastro-bot-screen-bg',
            self::OPTION_BOT_ACCENT_COLOR => '--openastro-bot-accent',
            self::OPTION_BOT_USER_BUBBLE_BG_COLOR => '--openastro-bot-user-bubble-bg',
            self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR => '--openastro-bot-user-bubble-text',
            self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR => '--openastro-bot-assistant-bubble-bg',
            self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR => '--openastro-bot-assistant-bubble-text',
        ];
    }

    private static function bot_style_inline_vars(): string
    {
        $defaults = self::bot_style_defaults();
        $vars = [];
        foreach (self::bot_style_variable_map() as $option => $var_name) {
            $value = (string) self::get_option_value($option, $defaults[$option]);
            $vars[$var_name] = $value;
            $vars[str_replace('--openastro-', '--astrology-suite-', $var_name)] = $value;
        }

        $style = [];
        foreach ($vars as $key => $value) {
            $style[] = $key . ': ' . $value;
        }

        return implode('; ', $style);
    }

    public static function render_horoscope_style_preview_field(): void
    {
        self::render_horoscope_style_preview_admin_assets();
        ?>
        <div class="wp-block-astrology-suite-widget astrology-suite-horoscope-style-preview" style="<?php echo esc_attr(self::horoscope_style_inline_vars()); ?>">
            <section class="openastro-card openastro-horoscope-card">
                <span class="openastro-ephemeris-eyebrow openastro-horoscope-eyebrow"><?php esc_html_e('Daily Horoscope', 'astrology-suite-freeastroapi'); ?></span>
                <h3><span class="openastro-astro-glyph">x</span><?php esc_html_e('Aries - 2026-06-10', 'astrology-suite-freeastroapi'); ?></h3>
                <div class="openastro-horoscope-theme-row">
                    <span class="openastro-horoscope-theme"><?php esc_html_e('Energy', 'astrology-suite-freeastroapi'); ?></span>
                    <div class="openastro-horoscope-keywords">
                        <span><?php esc_html_e('Boldness', 'astrology-suite-freeastroapi'); ?></span>
                        <span><?php esc_html_e('Initiative', 'astrology-suite-freeastroapi'); ?></span>
                        <span><?php esc_html_e('Clarity', 'astrology-suite-freeastroapi'); ?></span>
                    </div>
                </div>
                <p class="openastro-horoscope-summary"><?php esc_html_e('A concise daily interpretation appears here, using the content returned by FreeAstroAPI.', 'astrology-suite-freeastroapi'); ?></p>
                <div class="openastro-horoscope-scores">
                    <span><strong><?php esc_html_e('Overall', 'astrology-suite-freeastroapi'); ?></strong>82</span>
                    <span><strong><?php esc_html_e('Love', 'astrology-suite-freeastroapi'); ?></strong>80</span>
                    <span><strong><?php esc_html_e('Career', 'astrology-suite-freeastroapi'); ?></strong>84</span>
                </div>
                <details class="openastro-horoscope-score-factors" open>
                    <summary><?php esc_html_e('Why these scores?', 'astrology-suite-freeastroapi'); ?></summary>
                    <div><section><h4><?php esc_html_e('Career', 'astrology-suite-freeastroapi'); ?></h4><p><?php esc_html_e('Score explanations use the same card accent and text styling.', 'astrology-suite-freeastroapi'); ?></p></section></div>
                </details>
                <div class="openastro-horoscope-personal">
                    <h4><?php esc_html_e('Personal timing', 'astrology-suite-freeastroapi'); ?></h4>
                    <div class="openastro-horoscope-personal-meta">
                        <span><?php esc_html_e('Action & Energy', 'astrology-suite-freeastroapi'); ?></span>
                        <span><?php esc_html_e('Confidence: 95', 'astrology-suite-freeastroapi'); ?></span>
                    </div>
                    <section>
                        <h5><?php esc_html_e('Top personal transits', 'astrology-suite-freeastroapi'); ?></h5>
                        <div class="openastro-horoscope-transits">
                            <article>
                                <h6><?php esc_html_e('Mars Square Natal True Node', 'astrology-suite-freeastroapi'); ?></h6>
                                <p class="openastro-horoscope-transit-meta"><?php esc_html_e('Score 94.7 · Exact 17:35', 'astrology-suite-freeastroapi'); ?></p>
                                <p><?php esc_html_e('Personal transit interpretation text uses the tile, chip, and accent styles configured below.', 'astrology-suite-freeastroapi'); ?></p>
                            </article>
                        </div>
                    </section>
                    <section class="openastro-horoscope-timing">
                        <h5><?php esc_html_e('Timing context', 'astrology-suite-freeastroapi'); ?></h5>
                        <p><strong><?php esc_html_e('Next shift:', 'astrology-suite-freeastroapi'); ?></strong> <?php esc_html_e('Mars Square Natal True Node exact', 'astrology-suite-freeastroapi'); ?></p>
                    </section>
                </div>
                <dl class="openastro-horoscope-lucky">
                    <div><dt><?php esc_html_e('Color', 'astrology-suite-freeastroapi'); ?></dt><dd><?php esc_html_e('Red', 'astrology-suite-freeastroapi'); ?></dd></div>
                    <div><dt><?php esc_html_e('Time', 'astrology-suite-freeastroapi'); ?></dt><dd>11am - 4pm</dd></div>
                </dl>
                <div class="openastro-horoscope-astro">
                    <span><?php esc_html_e('Moon Sign: Aries', 'astrology-suite-freeastroapi'); ?></span>
                    <span><?php esc_html_e('Last Quarter', 'astrology-suite-freeastroapi'); ?></span>
                </div>
            </section>
        </div>
        <p class="description"><?php esc_html_e('Preview starts with saved horoscope colors and updates as you edit the controls below. Section visibility applies to Daily Sign and Personalized Horoscope results on the frontend after saving.', 'astrology-suite-freeastroapi'); ?></p>
        <?php
    }

    public static function render_horoscope_sections_field(): void
    {
        $defaults = self::horoscope_display_defaults();
        echo '<fieldset class="astrology-suite-horoscope-section-grid">';
        foreach (self::horoscope_display_labels() as $option => $label) {
            $value = (bool) self::get_option_value($option, $defaults[$option]);
            echo '<label><input type="checkbox" name="' . esc_attr($option) . '" value="1" ' . checked($value, true, false) . ' /> ' . esc_html($label) . '</label>';
        }
        echo '</fieldset>';
        echo '<p class="description">' . esc_html__('Unchecked sections are hidden from Daily Sign and Personalized Horoscope blocks and shortcodes.', 'astrology-suite-freeastroapi') . '</p>';
    }

    public static function render_horoscope_card_bg_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CARD_BG_COLOR, '#ffffff'); }
    public static function render_horoscope_card_text_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CARD_TEXT_COLOR, '#111111'); }
    public static function render_horoscope_card_border_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CARD_BORDER_COLOR, '#e5e7eb'); }
    public static function render_horoscope_theme_bg_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_THEME_BG_COLOR, '#111827'); }
    public static function render_horoscope_theme_text_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_THEME_TEXT_COLOR, '#ffffff'); }
    public static function render_horoscope_chip_bg_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CHIP_BG_COLOR, '#f9fafb'); }
    public static function render_horoscope_chip_text_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CHIP_TEXT_COLOR, '#111111'); }
    public static function render_horoscope_chip_border_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_CHIP_BORDER_COLOR, '#e5e7eb'); }
    public static function render_horoscope_tile_bg_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_TILE_BG_COLOR, '#f9fafb'); }
    public static function render_horoscope_tile_text_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_TILE_TEXT_COLOR, '#111111'); }
    public static function render_horoscope_tile_border_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_TILE_BORDER_COLOR, '#e5e7eb'); }
    public static function render_horoscope_accent_color_field(): void { self::render_color_field(self::OPTION_HOROSCOPE_ACCENT_COLOR, '#2563eb'); }

    public static function render_style_group_bot(): void
    {
        echo '<p><strong>' . esc_html__('Astrology Bot display and styling', 'astrology-suite-freeastroapi') . '</strong></p>';
        echo '<p class="description">' . esc_html(function_exists('wp_ai_client_prompt') ? __('Controls the embedded Western/Vedic chat component. WordPress 7.0+ uses the Google provider connected under Settings > Connectors; the FreeAstroAPI key powers astrology tool calls.', 'astrology-suite-freeastroapi') : __('Controls the embedded Western/Vedic chat component. The API tab stores the Gemini answer-layer key; the FreeAstroAPI key powers astrology tool calls.', 'astrology-suite-freeastroapi')) . '</p>';
    }

    public static function render_bot_style_preview_field(): void
    {
        self::render_bot_style_preview_admin_assets();
        ?>
        <div class="wp-block-astrology-suite-widget astrology-suite-bot-style-preview" style="<?php echo esc_attr(self::bot_style_inline_vars()); ?>">
            <div class="openastro-bot-phone">
                <div class="openastro-bot-header">
                    <div>
                        <span class="openastro-bot-kicker"><?php esc_html_e('Astrology Bot', 'astrology-suite-freeastroapi'); ?></span>
                        <h3><?php esc_html_e('Western Astrologer', 'astrology-suite-freeastroapi'); ?></h3>
                    </div>
                    <span class="openastro-bot-status-pill"><?php esc_html_e('Online', 'astrology-suite-freeastroapi'); ?></span>
                </div>
                <div class="openastro-bot-screen">
                    <div class="openastro-bot-message openastro-bot-message-assistant">
                        <p><?php esc_html_e('Add your birth details, then ask a chart or timing question.', 'astrology-suite-freeastroapi'); ?></p>
                    </div>
                    <div class="openastro-bot-message openastro-bot-message-user">
                        <p><?php esc_html_e('What should I focus on today?', 'astrology-suite-freeastroapi'); ?></p>
                    </div>
                </div>
                <div class="openastro-bot-composer">
                    <input type="text" class="openastro-bot-input" value="<?php echo esc_attr__('Ask the astrologer...', 'astrology-suite-freeastroapi'); ?>" readonly />
                    <button type="button" class="openastro-bot-send"><?php esc_html_e('Send', 'astrology-suite-freeastroapi'); ?></button>
                </div>
            </div>
        </div>
        <p class="description"><?php esc_html_e('Preview starts with saved bot colors and updates as you edit the controls below. The frontend bot uses the same color variables after saving.', 'astrology-suite-freeastroapi'); ?></p>
        <?php
    }

    public static function render_bot_shell_bg_color_field(): void { self::render_color_field(self::OPTION_BOT_SHELL_BG_COLOR, '#101014'); }
    public static function render_bot_header_bg_color_field(): void { self::render_color_field(self::OPTION_BOT_HEADER_BG_COLOR, '#17231f'); }
    public static function render_bot_screen_bg_color_field(): void { self::render_color_field(self::OPTION_BOT_SCREEN_BG_COLOR, '#0f1f1b'); }
    public static function render_bot_accent_color_field(): void { self::render_color_field(self::OPTION_BOT_ACCENT_COLOR, '#facc15'); }
    public static function render_bot_user_bubble_bg_color_field(): void { self::render_color_field(self::OPTION_BOT_USER_BUBBLE_BG_COLOR, '#d8f7c5'); }
    public static function render_bot_user_bubble_text_color_field(): void { self::render_color_field(self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR, '#142117'); }
    public static function render_bot_assistant_bubble_bg_color_field(): void { self::render_color_field(self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR, '#202c28'); }
    public static function render_bot_assistant_bubble_text_color_field(): void { self::render_color_field(self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR, '#f1f5f9'); }

    private static function render_horoscope_style_preview_admin_assets(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-horoscope-style-preview {
                max-width: 920px;
            }
            .astrology-suite-horoscope-section-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 8px 16px;
                max-width: 920px;
            }
            .astrology-suite-horoscope-section-grid label {
                display: flex;
                gap: 8px;
                align-items: center;
            }
        </style>
        <script>
            (function () {
                function initAstrologySuiteHoroscopeStylePreview() {
                    var preview = document.querySelector('.astrology-suite-horoscope-style-preview');
                    if (!preview) {
                        return;
                    }
                    var fields = <?php echo wp_json_encode(self::horoscope_style_variable_map()); ?>;
                    function setPreviewVariable(name, value) {
                        preview.style.setProperty(name, value);
                        if (name.indexOf('--openastro-') === 0) {
                            preview.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
                        }
                    }
                    Object.keys(fields).forEach(function (name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        if (!field) {
                            return;
                        }
                        var update = function () {
                            if (field.value) {
                                setPreviewVariable(fields[name], field.value);
                            }
                        };
                        field.addEventListener('input', update);
                        field.addEventListener('change', update);
                    });
                }
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAstrologySuiteHoroscopeStylePreview);
                } else {
                    initAstrologySuiteHoroscopeStylePreview();
                }
            })();
        </script>
        <?php
    }

    private static function render_bot_style_preview_admin_assets(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-bot-style-preview {
                max-width: 520px;
            }
            .astrology-suite-bot-style-preview .openastro-bot-phone {
                max-width: 430px;
            }
        </style>
        <script>
            (function () {
                function initAstrologySuiteBotStylePreview() {
                    var preview = document.querySelector('.astrology-suite-bot-style-preview');
                    if (!preview) {
                        return;
                    }
                    var fields = <?php echo wp_json_encode(self::bot_style_variable_map()); ?>;
                    function setPreviewVariable(name, value) {
                        preview.style.setProperty(name, value);
                        if (name.indexOf('--openastro-') === 0) {
                            preview.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
                        }
                    }
                    Object.keys(fields).forEach(function (name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        if (!field) {
                            return;
                        }
                        var update = function () {
                            if (field.value) {
                                setPreviewVariable(fields[name], field.value);
                            }
                        };
                        field.addEventListener('input', update);
                        field.addEventListener('change', update);
                    });
                }
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAstrologySuiteBotStylePreview);
                } else {
                    initAstrologySuiteBotStylePreview();
                }
            })();
        </script>
        <?php
    }

    private static function ephemeris_style_defaults(): array
    {
        return [
            self::OPTION_EPHEMERIS_TABLE_BG_COLOR => '#ffffff',
            self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR => '#111111',
            self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR => '#e5e7eb',
            self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR => '#f8fafc',
            self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR => '#111111',
            self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR => '#f8fafc',
            self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR => '#475569',
            self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR => '#f8fafc',
            self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR => '#2563eb',
            self::OPTION_EPHEMERIS_RETROGRADE_COLOR => '#b91c1c',
            self::OPTION_EPHEMERIS_INGRESS_COLOR => '#1d4ed8',
            self::OPTION_EPHEMERIS_STATION_COLOR => '#a16207',
        ];
    }

    private static function ephemeris_style_variable_map(): array
    {
        return [
            self::OPTION_EPHEMERIS_TABLE_BG_COLOR => '--openastro-ephemeris-table-bg',
            self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR => '--openastro-ephemeris-table-text',
            self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR => '--openastro-ephemeris-table-border',
            self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR => '--openastro-ephemeris-header-bg',
            self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR => '--openastro-ephemeris-header-text',
            self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR => '--openastro-ephemeris-badge-bg',
            self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR => '--openastro-ephemeris-badge-text',
            self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR => '--openastro-ephemeris-today-bg',
            self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR => '--openastro-ephemeris-today-accent',
            self::OPTION_EPHEMERIS_RETROGRADE_COLOR => '--openastro-ephemeris-retrograde',
            self::OPTION_EPHEMERIS_INGRESS_COLOR => '--openastro-ephemeris-ingress',
            self::OPTION_EPHEMERIS_STATION_COLOR => '--openastro-ephemeris-station',
        ];
    }

    private static function ephemeris_style_inline_vars(): string
    {
        $defaults = self::ephemeris_style_defaults();
        $vars = [];
        foreach (self::ephemeris_style_variable_map() as $option => $var_name) {
            $value = (string) self::get_option_value($option, $defaults[$option]);
            $vars[$var_name] = $value;
            $vars[str_replace('--openastro-', '--astrology-suite-', $var_name)] = $value;
        }

        $style = [];
        foreach ($vars as $key => $value) {
            $style[] = $key . ': ' . $value;
        }

        return implode('; ', $style);
    }

    private static function render_color_field(string $option, string $default): void
    {
        $value = (string) self::get_option_value($option, $default);
        echo '<input type="color" name="' . esc_attr($option) . '" value="' . esc_attr($value) . '" />';
    }

    public static function render_ephemeris_style_preview_field(): void
    {
        self::render_ephemeris_style_preview_admin_assets();
        ?>
        <div class="wp-block-astrology-suite-widget astrology-suite-ephemeris-style-preview" style="<?php echo esc_attr(self::ephemeris_style_inline_vars()); ?>">
            <section class="openastro-ephemeris-table-card">
                <div class="openastro-ephemeris-table-top">
                    <span class="openastro-ephemeris-table-meta"><?php esc_html_e('GMT 00:00', 'astrology-suite-freeastroapi'); ?></span>
                    <h3><?php esc_html_e('June 2026', 'astrology-suite-freeastroapi'); ?></h3>
                    <span class="openastro-ephemeris-row-badge"><?php esc_html_e('30 rows', 'astrology-suite-freeastroapi'); ?></span>
                </div>
                <div class="openastro-ephemeris-table-viewport">
                    <table class="openastro-ephemeris-table">
                        <thead>
                            <tr>
                                <th class="openastro-ephemeris-col-date"><?php esc_html_e('Date', 'astrology-suite-freeastroapi'); ?></th>
                                <th><?php esc_html_e('Day', 'astrology-suite-freeastroapi'); ?></th>
                                <th><?php esc_html_e('GST', 'astrology-suite-freeastroapi'); ?></th>
                                <th><span class="openastro-ephemeris-head-body"><span class="openastro-ephemeris-glyph">☉</span><span class="openastro-ephemeris-head-label">SUN</span></span></th>
                                <th><span class="openastro-ephemeris-head-body"><span class="openastro-ephemeris-glyph">☽</span><span class="openastro-ephemeris-head-label">MOON</span></span></th>
                                <th><span class="openastro-ephemeris-head-body"><span class="openastro-ephemeris-glyph">☿</span><span class="openastro-ephemeris-head-label">MERCURY</span></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="openastro-ephemeris-date-cell">1</td>
                                <td class="openastro-ephemeris-day-cell">Mon</td>
                                <td class="openastro-ephemeris-gst-cell">16:37:59</td>
                                <td>10°32' Gem</td>
                                <td>17°29' Sag</td>
                                <td class="openastro-ephemeris-cell is-ingress">29°09' Gem</td>
                            </tr>
                            <tr class="openastro-ephemeris-row is-today">
                                <td class="openastro-ephemeris-date-cell">2</td>
                                <td class="openastro-ephemeris-day-cell">Tue</td>
                                <td class="openastro-ephemeris-gst-cell">16:41:55</td>
                                <td>11°30' Gem</td>
                                <td>29°21' Sag</td>
                                <td>00°51' Can</td>
                            </tr>
                            <tr>
                                <td class="openastro-ephemeris-date-cell">3</td>
                                <td class="openastro-ephemeris-day-cell">Wed</td>
                                <td class="openastro-ephemeris-gst-cell">16:45:52</td>
                                <td>12°27' Gem</td>
                                <td>11°13' Cap</td>
                                <td class="openastro-ephemeris-cell is-retrograde">02°30' Can Rx</td>
                            </tr>
                            <tr>
                                <td class="openastro-ephemeris-date-cell">4</td>
                                <td class="openastro-ephemeris-day-cell">Thu</td>
                                <td class="openastro-ephemeris-gst-cell">16:49:49</td>
                                <td>13°25' Gem</td>
                                <td>23°08' Cap</td>
                                <td class="openastro-ephemeris-cell is-station">04°06' Can S</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <p class="description"><?php esc_html_e('Preview starts with the saved ephemeris table colors and updates as you edit the controls below. Save changes to apply them on the frontend.', 'astrology-suite-freeastroapi'); ?></p>
        <?php
    }

    private static function render_ephemeris_style_preview_admin_assets(): void
    {
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;
        ?>
        <style>
            .astrology-suite-ephemeris-style-preview {
                max-width: 1120px;
                padding: 16px;
                overflow: hidden;
            }
            .astrology-suite-ephemeris-style-preview .openastro-ephemeris-table {
                min-width: 680px;
            }
        </style>
        <script>
            (function () {
                function initAstrologySuiteEphemerisStylePreview() {
                    var preview = document.querySelector('.astrology-suite-ephemeris-style-preview');
                    if (!preview) {
                        return;
                    }
                    var fields = <?php echo wp_json_encode(self::ephemeris_style_variable_map()); ?>;
                    function setPreviewVariable(name, value) {
                        preview.style.setProperty(name, value);
                        if (name.indexOf('--openastro-') === 0) {
                            preview.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
                        }
                    }
                    Object.keys(fields).forEach(function (name) {
                        var field = document.querySelector('[name="' + name + '"]');
                        if (!field) {
                            return;
                        }
                        var update = function () {
                            if (field.value) {
                                setPreviewVariable(fields[name], field.value);
                            }
                        };
                        field.addEventListener('input', update);
                        field.addEventListener('change', update);
                    });
                }
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAstrologySuiteEphemerisStylePreview);
                } else {
                    initAstrologySuiteEphemerisStylePreview();
                }
            })();
        </script>
        <?php
    }

    public static function render_ephemeris_table_bg_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_BG_COLOR, '#ffffff'); }
    public static function render_ephemeris_table_text_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR, '#111111'); }
    public static function render_ephemeris_table_border_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR, '#e5e7eb'); }
    public static function render_ephemeris_table_header_bg_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR, '#f8fafc'); }
    public static function render_ephemeris_table_header_text_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR, '#111111'); }
    public static function render_ephemeris_table_badge_bg_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR, '#f8fafc'); }
    public static function render_ephemeris_table_badge_text_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR, '#475569'); }
    public static function render_ephemeris_table_today_bg_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR, '#f8fafc'); }
    public static function render_ephemeris_table_today_accent_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR, '#2563eb'); }
    public static function render_ephemeris_retrograde_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_RETROGRADE_COLOR, '#b91c1c'); }
    public static function render_ephemeris_ingress_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_INGRESS_COLOR, '#1d4ed8'); }
    public static function render_ephemeris_station_color_field(): void { self::render_color_field(self::OPTION_EPHEMERIS_STATION_COLOR, '#a16207'); }

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
    public static function render_typography_input_field(): void { self::render_font_size_field(self::OPTION_TYPO_INPUT, 16); }
    public static function render_typography_button_field(): void { self::render_font_size_field(self::OPTION_TYPO_BUTTON, 16); }
    public static function render_typography_meta_field(): void { self::render_font_size_field(self::OPTION_TYPO_META, 13); }

    private static function settings_tabs(): array
    {
        return [
            'api' => __('API', 'astrology-suite-freeastroapi'),
            'report' => __('Report', 'astrology-suite-freeastroapi'),
            'chart' => __('Custom Chart', 'astrology-suite-freeastroapi'),
            'vedic-chart' => __('Vedic Astrology', 'astrology-suite-freeastroapi'),
            'objects' => __('Objects', 'astrology-suite-freeastroapi'),
            'style' => __('Style', 'astrology-suite-freeastroapi'),
            'ephemeris' => __('Ephemeris Style', 'astrology-suite-freeastroapi'),
            'typography' => __('Typography', 'astrology-suite-freeastroapi'),
            'help' => __('Help', 'astrology-suite-freeastroapi'),
        ];
    }

    private static function vedic_settings_sections(): array
    {
        return [
            'essentials' => __('Essentials', 'astrology-suite-freeastroapi'),
            'charts' => __('Charts', 'astrology-suite-freeastroapi'),
            'workbench' => __('Workbench', 'astrology-suite-freeastroapi'),
            'appearance' => __('Appearance', 'astrology-suite-freeastroapi'),
            'preview' => __('Preview', 'astrology-suite-freeastroapi'),
        ];
    }

    private static function active_vedic_settings_section(): string
    {
        $section_param = filter_input(INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $section = is_string($section_param) ? sanitize_key(wp_unslash($section_param)) : 'essentials';
        $sections = self::vedic_settings_sections();

        return isset($sections[$section]) ? $section : 'essentials';
    }

    private static function active_settings_tab(): string
    {
        $tab_param = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tab = is_string($tab_param) ? sanitize_key(wp_unslash($tab_param)) : 'api';
        $tabs = self::settings_tabs();
        return isset($tabs[$tab]) ? $tab : 'api';
    }

    private static function settings_group_for_tab(string $tab): string
    {
        $groups = [
            'api' => 'astrology_suite_api_settings_group',
            'report' => 'astrology_suite_report_settings_group',
            'chart' => 'astrology_suite_chart_settings_group',
            'vedic-chart' => 'astrology_suite_vedic_chart_settings_group',
            'objects' => 'astrology_suite_objects_settings_group',
            'style' => 'astrology_suite_style_settings_group',
            'ephemeris' => 'astrology_suite_ephemeris_settings_group',
            'typography' => 'astrology_suite_typography_settings_group',
        ];

        return $groups[$tab] ?? $groups['api'];
    }

    private static function render_section_fields(string $section_id): void
    {
        global $wp_settings_fields;

        if (empty($wp_settings_fields[self::SETTINGS_API_PAGE][$section_id])) {
            return;
        }

        echo '<table class="form-table" role="presentation">';
        do_settings_fields(self::SETTINGS_API_PAGE, $section_id);
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
        if ($tab === 'chart') {
            self::render_section_fields('openastro_chart_settings');
            return;
        }
        if ($tab === 'vedic-chart') {
            $section = self::active_vedic_settings_section();
            $section_ids = [
                'essentials' => 'openastro_vedic_essentials_settings',
                'charts' => 'openastro_vedic_chart_settings',
                'workbench' => 'openastro_vedic_workbench_settings',
                'appearance' => 'openastro_vedic_appearance_settings',
                'preview' => 'openastro_vedic_preview_settings',
            ];
            self::render_section_fields($section_ids[$section]);
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
        if ($tab === 'ephemeris') {
            self::render_section_fields('openastro_ephemeris_style_settings');
            return;
        }
        if ($tab === 'typography') {
            self::render_section_fields('openastro_typography_settings');
        }
    }

    private static function render_vedic_settings_subtabs(string $active_section): void
    {
        ?>
        <nav class="nav-tab-wrapper astrology-suite-vedic-subtabs" aria-label="<?php esc_attr_e('Vedic Astrology Settings', 'astrology-suite-freeastroapi'); ?>">
            <?php foreach (self::vedic_settings_sections() as $slug => $label) : ?>
                <?php
                $url = add_query_arg(
                    ['page' => self::SETTINGS_PAGE_SLUG, 'tab' => 'vedic-chart', 'section' => $slug],
                    admin_url('options-general.php')
                );
                ?>
                <a href="<?php echo esc_url($url); ?>" class="nav-tab <?php echo $active_section === $slug ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <style>
            .astrology-suite-vedic-subtabs { margin: 14px 0 18px; }
            .astrology-suite-vedic-subtabs .nav-tab { padding: 7px 12px; }
        </style>
        <?php
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
                    <?php $tab_url = add_query_arg(['page' => self::SETTINGS_PAGE_SLUG, 'tab' => $slug], admin_url('options-general.php')); ?>
                    <a href="<?php echo esc_url($tab_url); ?>" class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <?php if ($active_tab !== 'help') : ?>
                <?php if ($active_tab === 'vedic-chart') : ?>
                    <?php self::render_vedic_settings_subtabs(self::active_vedic_settings_section()); ?>
                <?php endif; ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields(self::settings_group_for_tab($active_tab));
                    self::render_tabbed_settings_fields($active_tab);
                    if ($active_tab !== 'vedic-chart' || self::active_vedic_settings_section() !== 'preview') {
                        submit_button();
                    }
                    ?>
                </form>
            <?php else : ?>
                <style>
                    .astrology-suite-help-wrap { max-width: 1180px; }
                    .astrology-suite-help-lead { max-width: 900px; font-size: 15px; line-height: 1.65; }
                    .astrology-suite-help-grid,
                    .astrology-suite-help-gallery {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                        gap: 16px;
                        margin: 16px 0 24px;
                    }
                    .astrology-suite-help-card,
                    .astrology-suite-help-gallery figure {
                        box-sizing: border-box;
                        margin: 0;
                        padding: 18px;
                        border: 1px solid #dcdcde;
                        border-radius: 8px;
                        background: #fff;
                    }
                    .astrology-suite-help-card h3 { margin-top: 0; }
                    .astrology-suite-help-callout {
                        max-width: 900px;
                        margin: 16px 0 24px;
                        padding: 14px 16px;
                        border-left: 4px solid #2271b1;
                        background: #fff;
                    }
                    .astrology-suite-help-gallery img {
                        display: block;
                        width: 100%;
                        height: 220px;
                        border: 1px solid #dcdcde;
                        border-radius: 6px;
                        object-fit: cover;
                        object-position: top center;
                    }
                    .astrology-suite-help-gallery figcaption { margin-top: 12px; line-height: 1.5; }
                </style>
                <div class="astrology-suite-help-wrap">
                    <h2><?php esc_html_e('Start Here', 'astrology-suite-freeastroapi'); ?></h2>
                    <p class="astrology-suite-help-lead"><?php esc_html_e('Astrology Suite turns one site-owned FreeAstroAPI connection into Western and Vedic WordPress experiences. Add a block or shortcode, let visitors enter their details, and keep API credentials behind the server-side proxy.', 'astrology-suite-freeastroapi'); ?></p>
                    <div class="astrology-suite-help-callout">
                        <strong><?php esc_html_e('API ownership and plan limits:', 'astrology-suite-freeastroapi'); ?></strong>
                        <?php esc_html_e('The WordPress site owner supplies the FreeAstroAPI key once in the API tab. Visitors never enter or receive it. Set API requests per second to the current allowance for that key\'s plan; the safe default is 1.', 'astrology-suite-freeastroapi'); ?>
                    </div>

                    <div class="astrology-suite-help-grid">
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('1. Connect', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Open the API tab, save the site owner\'s FreeAstroAPI key, match its requests-per-second limit, and decide whether public visitors may calculate.', 'astrology-suite-freeastroapi'); ?></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('2. Choose an Experience', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Use a natal, sign, ephemeris, horoscope, Vedic chart, Vedic workbench, or astrology bot block according to the page\'s purpose.', 'astrology-suite-freeastroapi'); ?></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('3. Add It to a Page', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Search for “Astrology Suite” in the block inserter. Classic content and template areas can use the equivalent shortcode.', 'astrology-suite-freeastroapi'); ?></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('4. Style and Test', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Set chart and widget styles, use the live previews, then test a real calculation while logged out before publishing.', 'astrology-suite-freeastroapi'); ?></p>
                        </section>
                    </div>

                    <h2><?php esc_html_e('Choose the Right Experience', 'astrology-suite-freeastroapi'); ?></h2>
                    <div class="astrology-suite-help-grid">
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Natal Charts and Reports', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Publish a Western natal wheel, interpretation report, or both. Natal forms support city autocomplete and an unknown-birth-time path when houses and angles are not required.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_chart_report]</code></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Sun, Moon, Rising, and Midheaven', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Use a focused sign widget when a full chart is unnecessary. Interpretation text can be enabled and shown in improved or poetic style.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_simple_sign_sun]</code></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Monthly Ephemeris', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Create tropical or sidereal planetary tables with selectable bodies, month navigation, optional local points, event markers, and CSV download.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_ephemeris]</code></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Daily Horoscopes', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Choose a sign-based daily horoscope for broad engagement or a personalized horoscope for birth-data-based timing and transit context.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_daily_horoscope]</code></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Vedic Astrology', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Use Vedic Visual Chart for focused D1, D9, and other varga charts. Use Vedic Astrology Workbench for grahas, dashas, yogas, Panchang, strength modules, and practitioner references. The Vedic Astrology settings tab keeps defaults, workbench modules, appearance, and previews together.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_vedic_chart]</code> <code>[astrology_suite_vedic_workbench]</code></p>
                        </section>
                        <section class="astrology-suite-help-card">
                            <h3><?php esc_html_e('Astrology Bot', 'astrology-suite-freeastroapi'); ?></h3>
                            <p><?php esc_html_e('Add a Western and Vedic chat experience with birth-profile collection. It uses the site-owned FreeAstroAPI key; WordPress 7.0+ also requires a Google provider connection under Settings > Connectors.', 'astrology-suite-freeastroapi'); ?></p>
                            <p><code>[astrology_suite_astrology_bot]</code></p>
                        </section>
                    </div>

                    <p class="astrology-suite-help-lead"><?php esc_html_e('Western natal calculations can use the unknown-time option, although Rising and Midheaven need a known time. Vedic charts and workbench calculations require an exact birth time because vargas, houses, dashas, and timing references depend on it.', 'astrology-suite-freeastroapi'); ?></p>

                    <h2><?php esc_html_e('Feature Screenshots', 'astrology-suite-freeastroapi'); ?></h2>
                    <p class="astrology-suite-help-lead"><?php esc_html_e('These screenshots highlight the newest Vedic chart and workbench features. The block guide and reference sections on this page cover every Western and Vedic experience included with the plugin.', 'astrology-suite-freeastroapi'); ?></p>
                    <div class="astrology-suite-help-gallery">
                        <figure>
                            <img src="<?php echo esc_url(OPENASTRO_SUITE_URL . 'assets/screenshots/screenshot-12-vedic-visual-chart.png'); ?>" alt="<?php esc_attr_e('Generated D1 and D9 Vedic visual charts', 'astrology-suite-freeastroapi'); ?>" />
                            <figcaption><?php esc_html_e('Frontend Vedic Visual Chart with D1 Rashi and D9 Navamsha.', 'astrology-suite-freeastroapi'); ?></figcaption>
                        </figure>
                        <figure>
                            <img src="<?php echo esc_url(OPENASTRO_SUITE_URL . 'assets/screenshots/screenshot-13-vedic-chart-settings.png'); ?>" alt="<?php esc_attr_e('Vedic Chart settings and generated preview', 'astrology-suite-freeastroapi'); ?>" />
                            <figcaption><?php esc_html_e('Dedicated Vedic Chart settings with generated preview.', 'astrology-suite-freeastroapi'); ?></figcaption>
                        </figure>
                        <figure>
                            <img src="<?php echo esc_url(OPENASTRO_SUITE_URL . 'assets/screenshots/screenshot-14-vedic-style-preview.png'); ?>" alt="<?php esc_attr_e('Vedic result style preview in plugin settings', 'astrology-suite-freeastroapi'); ?>" />
                            <figcaption><?php esc_html_e('Instant Style-tab preview for Vedic result surfaces and tables.', 'astrology-suite-freeastroapi'); ?></figcaption>
                        </figure>
                        <figure>
                            <img src="<?php echo esc_url(OPENASTRO_SUITE_URL . 'assets/screenshots/screenshot-15-vedic-workbench.png'); ?>" alt="<?php esc_attr_e('Vedic Astrology Workbench overview and graha table', 'astrology-suite-freeastroapi'); ?>" />
                            <figcaption><?php esc_html_e('Vedic Astrology Workbench with overview and practitioner references.', 'astrology-suite-freeastroapi'); ?></figcaption>
                        </figure>
                    </div>

                <h3><?php esc_html_e('Available Blocks', 'astrology-suite-freeastroapi'); ?></h3>
                <table class="widefat striped" style="max-width: 920px;">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Block', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Front-end output', 'astrology-suite-freeastroapi'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>astrology-suite/chart-only</code></td>
                            <td><?php esc_html_e('Input form plus natal wheel chart. Report text is not shown.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/report-only</code></td>
                            <td><?php esc_html_e('Input form plus interpretation report. Natal wheel chart is not shown.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/chart-report</code></td>
                            <td><?php esc_html_e('Input form plus natal wheel chart and interpretation report.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/simple-sign-sun</code></td>
                            <td><?php esc_html_e('Input form plus Sun sign result.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/simple-sign-moon</code></td>
                            <td><?php esc_html_e('Input form plus Moon sign result.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/simple-sign-rising</code></td>
                            <td><?php esc_html_e('Input form plus Rising sign result.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/simple-sign-midheaven</code></td>
                            <td><?php esc_html_e('Input form plus Midheaven sign result.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/ephemeris</code></td>
                            <td><?php esc_html_e('Monthly ephemeris table with selectable bodies, tropical/sidereal mode, optional city, and CSV download.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/daily-horoscope</code></td>
                            <td><?php esc_html_e('Daily sign horoscope with scores, lucky attributes, guidance, and sky highlights returned by FreeAstroAPI.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/personalized-horoscope</code></td>
                            <td><?php esc_html_e('Personalized daily horoscope from submitted birth details, current sky timing, scores, and personal transit context.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/vedic-chart</code></td>
                            <td><?php esc_html_e('Vedic D1, D9, and other supported divisional charts in North, South, or East Indian layouts.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/vedic-workbench</code></td>
                            <td><?php esc_html_e('Vedic practitioner reference with grahas, vargas, Vimshottari dashas, yogas, Panchang, Shadbala, and Ashtakavarga.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                        <tr>
                            <td><code>astrology-suite/astrology-bot</code></td>
                            <td><?php esc_html_e('Western/Vedic astrology chat component with birth profile collection and server-side FreeAstroAPI + Gemini requests.', 'astrology-suite-freeastroapi'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <p><?php esc_html_e('Simple-sign blocks include sidebar controls for interpretation text and interpretation style. The supported style values are "improved" and "poetic".', 'astrology-suite-freeastroapi'); ?></p>

                <h3><?php esc_html_e('Code Block Example (Page Code Editor)', 'astrology-suite-freeastroapi'); ?></h3>
                <pre><code><?php echo esc_html("<!-- wp:astrology-suite/chart-only /-->\n<!-- wp:astrology-suite/report-only /-->\n<!-- wp:astrology-suite/chart-report /-->\n<!-- wp:astrology-suite/simple-sign-sun {\"includeInterpretation\":true,\"interpretationStyle\":\"improved\"} /-->\n<!-- wp:astrology-suite/simple-sign-moon {\"includeInterpretation\":false,\"interpretationStyle\":\"improved\"} /-->\n<!-- wp:astrology-suite/simple-sign-rising {\"includeInterpretation\":true,\"interpretationStyle\":\"poetic\"} /-->\n<!-- wp:astrology-suite/simple-sign-midheaven {\"includeInterpretation\":true,\"interpretationStyle\":\"improved\"} /-->\n<!-- wp:astrology-suite/ephemeris /-->\n<!-- wp:astrology-suite/daily-horoscope /-->\n<!-- wp:astrology-suite/personalized-horoscope /-->\n<!-- wp:astrology-suite/vedic-chart /-->\n<!-- wp:astrology-suite/vedic-workbench /-->\n<!-- wp:astrology-suite/astrology-bot /-->"); ?></code></pre>

                <h3><?php esc_html_e('Shortcode Examples', 'astrology-suite-freeastroapi'); ?></h3>
                <p><?php esc_html_e('Use shortcodes in classic editor content, shortcode blocks, widgets, or template areas that run WordPress shortcodes.', 'astrology-suite-freeastroapi'); ?></p>
                <pre><code><?php echo esc_html("[astrology_suite mode=\"chart-report\"]\n[astrology_suite mode=\"chart-report\" language=\"vi\"]\n[astrology_suite_chart_only]\n[astrology_suite_report_only]\n[astrology_suite_chart_report]\n[astrology_suite_simple_sign_sun include_interpretation=\"true\" interpretation_style=\"improved\"]\n[astrology_suite_simple_sign_moon include_interpretation=\"false\"]\n[astrology_suite_simple_sign_rising include_interpretation=\"true\" interpretation_style=\"poetic\"]\n[astrology_suite_simple_sign_midheaven]\n[astrology_suite_ephemeris]\n[astrology_suite_daily_horoscope]\n[astrology_suite_personalized_horoscope]\n[astrology_suite_vedic_chart]\n[astrology_suite_vedic_workbench]\n[astrology_suite_astrology_bot]"); ?></code></pre>
                <p><?php esc_html_e('Supported shortcode modes are chart-only, report-only, chart-report, simple-sign-sun, simple-sign-moon, simple-sign-rising, simple-sign-midheaven, ephemeris, daily-horoscope, personalized-horoscope, vedic-chart, vedic-workbench, and astrology-bot.', 'astrology-suite-freeastroapi'); ?></p>
                <p><?php esc_html_e('Shortcode attributes: mode, include_interpretation, interpretation_style, language/lang, class, and the Vedic overrides preset, layout, divisions, and modules. Example: [astrology_suite_vedic_workbench preset="custom" layout="south_indian" divisions="1,9,10" modules="yogas,panchang"]. Supported UI language values are auto, en, vi, fr, es, de, and pt. Legacy openastro, openastro_*, freeastro, and freeastro_* shortcode aliases are also registered.', 'astrology-suite-freeastroapi'); ?></p>

                <h3><?php esc_html_e('All Available Shortcodes', 'astrology-suite-freeastroapi'); ?></h3>
                <p><?php esc_html_e('Use the Astrology Suite shortcodes for new pages. The OpenAstro and FreeAstro names are legacy aliases kept for older content.', 'astrology-suite-freeastroapi'); ?></p>
                <?php
                $shortcode_rows = [
                    [
                        'mode' => 'chart-only',
                        'recommended' => '[astrology_suite mode="chart-only"]',
                        'named' => '[astrology_suite_chart_only]',
                        'openastro' => ['[openastro mode="chart-only"]', '[openastro_chart_only]'],
                        'freeastro' => ['[freeastro mode="chart-only"]', '[freeastro_chart_only]'],
                        'output' => __('Input form plus natal wheel chart. Report text is not shown.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'report-only',
                        'recommended' => '[astrology_suite mode="report-only"]',
                        'named' => '[astrology_suite_report_only]',
                        'openastro' => ['[openastro mode="report-only"]', '[openastro_report_only]'],
                        'freeastro' => ['[freeastro mode="report-only"]', '[freeastro_report_only]'],
                        'output' => __('Input form plus interpretation report. Natal wheel chart is not shown.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'chart-report',
                        'recommended' => '[astrology_suite mode="chart-report"]',
                        'named' => '[astrology_suite_chart_report]',
                        'openastro' => ['[openastro mode="chart-report"]', '[openastro_chart_report]'],
                        'freeastro' => ['[freeastro mode="chart-report"]', '[freeastro_chart_report]'],
                        'output' => __('Input form plus natal wheel chart and interpretation report.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'simple-sign-sun',
                        'recommended' => '[astrology_suite mode="simple-sign-sun"]',
                        'named' => '[astrology_suite_simple_sign_sun]',
                        'openastro' => ['[openastro mode="simple-sign-sun"]', '[openastro_simple_sign_sun]'],
                        'freeastro' => ['[freeastro mode="simple-sign-sun"]', '[freeastro_simple_sign_sun]'],
                        'output' => __('Input form plus Sun sign result.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'simple-sign-moon',
                        'recommended' => '[astrology_suite mode="simple-sign-moon"]',
                        'named' => '[astrology_suite_simple_sign_moon]',
                        'openastro' => ['[openastro mode="simple-sign-moon"]', '[openastro_simple_sign_moon]'],
                        'freeastro' => ['[freeastro mode="simple-sign-moon"]', '[freeastro_simple_sign_moon]'],
                        'output' => __('Input form plus Moon sign result.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'simple-sign-rising',
                        'recommended' => '[astrology_suite mode="simple-sign-rising"]',
                        'named' => '[astrology_suite_simple_sign_rising]',
                        'openastro' => ['[openastro mode="simple-sign-rising"]', '[openastro_simple_sign_rising]'],
                        'freeastro' => ['[freeastro mode="simple-sign-rising"]', '[freeastro_simple_sign_rising]'],
                        'output' => __('Input form plus Rising sign result.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'simple-sign-midheaven',
                        'recommended' => '[astrology_suite mode="simple-sign-midheaven"]',
                        'named' => '[astrology_suite_simple_sign_midheaven]',
                        'openastro' => ['[openastro mode="simple-sign-midheaven"]', '[openastro_simple_sign_midheaven]'],
                        'freeastro' => ['[freeastro mode="simple-sign-midheaven"]', '[freeastro_simple_sign_midheaven]'],
                        'output' => __('Input form plus Midheaven sign result.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'ephemeris',
                        'recommended' => '[astrology_suite mode="ephemeris"]',
                        'named' => '[astrology_suite_ephemeris]',
                        'openastro' => ['[openastro mode="ephemeris"]', '[openastro_ephemeris]'],
                        'freeastro' => ['[freeastro mode="ephemeris"]', '[freeastro_ephemeris]'],
                        'output' => __('Monthly ephemeris table with selectable bodies, tropical/sidereal mode, optional city, and CSV download.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'daily-horoscope',
                        'recommended' => '[astrology_suite mode="daily-horoscope"]',
                        'named' => '[astrology_suite_daily_horoscope]',
                        'openastro' => ['[openastro mode="daily-horoscope"]', '[openastro_daily_horoscope]'],
                        'freeastro' => ['[freeastro mode="daily-horoscope"]', '[freeastro_daily_horoscope]'],
                        'output' => __('Daily sign horoscope with scores, lucky attributes, guidance, and sky highlights returned by FreeAstroAPI.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'personalized-horoscope',
                        'recommended' => '[astrology_suite mode="personalized-horoscope"]',
                        'named' => '[astrology_suite_personalized_horoscope]',
                        'openastro' => ['[openastro mode="personalized-horoscope"]', '[openastro_personalized_horoscope]'],
                        'freeastro' => ['[freeastro mode="personalized-horoscope"]', '[freeastro_personalized_horoscope]'],
                        'output' => __('Personalized daily horoscope from submitted birth details, current sky timing, scores, and personal transit context.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'vedic-chart',
                        'recommended' => '[astrology_suite mode="vedic-chart"]',
                        'named' => '[astrology_suite_vedic_chart]',
                        'openastro' => ['[openastro mode="vedic-chart"]', '[openastro_vedic_chart]'],
                        'freeastro' => ['[freeastro mode="vedic-chart"]', '[freeastro_vedic_chart]'],
                        'output' => __('Regional Vedic divisional chart with layout, theme, varga, node, and label controls.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'vedic-workbench',
                        'recommended' => '[astrology_suite mode="vedic-workbench"]',
                        'named' => '[astrology_suite_vedic_workbench]',
                        'openastro' => ['[openastro mode="vedic-workbench"]', '[openastro_vedic_workbench]'],
                        'freeastro' => ['[freeastro mode="vedic-workbench"]', '[freeastro_vedic_workbench]'],
                        'output' => __('Vedic visual chart plus grahas, vargas, dashas, yogas, Panchang, Shadbala, and Ashtakavarga.', 'astrology-suite-freeastroapi'),
                    ],
                    [
                        'mode' => 'astrology-bot',
                        'recommended' => '[astrology_suite mode="astrology-bot"]',
                        'named' => '[astrology_suite_astrology_bot]',
                        'openastro' => ['[openastro mode="astrology-bot"]', '[openastro_astrology_bot]'],
                        'freeastro' => ['[freeastro mode="astrology-bot"]', '[freeastro_astrology_bot]'],
                        'output' => __('Western/Vedic chat component with birth profile collection and server-side FreeAstroAPI + Gemini requests.', 'astrology-suite-freeastroapi'),
                    ],
                ];
                ?>
                <table class="widefat striped" style="max-width: 1180px;">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Mode', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Recommended', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Named shortcode', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Legacy OpenAstro aliases', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Legacy FreeAstro aliases', 'astrology-suite-freeastroapi'); ?></th>
                            <th><?php esc_html_e('Front-end output', 'astrology-suite-freeastroapi'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shortcode_rows as $shortcode_row) : ?>
                            <tr>
                                <td><code><?php echo esc_html($shortcode_row['mode']); ?></code></td>
                                <td><code><?php echo esc_html($shortcode_row['recommended']); ?></code></td>
                                <td><code><?php echo esc_html($shortcode_row['named']); ?></code></td>
                                <td>
                                    <?php foreach ($shortcode_row['openastro'] as $alias) : ?>
                                        <code><?php echo esc_html($alias); ?></code><br />
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php foreach ($shortcode_row['freeastro'] as $alias) : ?>
                                        <code><?php echo esc_html($alias); ?></code><br />
                                    <?php endforeach; ?>
                                </td>
                                <td><?php echo esc_html($shortcode_row['output']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><?php esc_html_e('Optional attributes can be added to supported shortcodes, for example language="vi", class="my-widget", include_interpretation="true", or interpretation_style="poetic".', 'astrology-suite-freeastroapi'); ?></p>

                <hr />
                <h2><?php esc_html_e('Settings Tabs', 'astrology-suite-freeastroapi'); ?></h2>
                <ul>
                    <li><?php esc_html_e('API stores the site owner’s FreeAstroAPI key, plan-aware requests-per-second limit, public calculation access, frontend widget language, and natal interpretation response language. WordPress 7.0+ configures the optional Astrology Bot through Settings > Connectors; legacy Gemini fields remain for older WordPress versions.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Report controls interpretation length, report limits, angle sections, birth context, planet aspect links, aspect glyphs, thematic interpretation, chart print button, and tables.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Custom Chart controls SVG/PNG chart theme bodies, FreeAstroAPI-generated preview cards, and optional advanced chart_config JSON.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Vedic Astrology groups reading presets, chart defaults, workbench modules, result appearance, and an on-demand cached preview into one tab. H1–H12 house labels remain hidden.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Objects controls the Western house system and which extra points are requested and rendered. The lunar-node selector permits True Node, Mean Node, or no node, but never both variants in one calculation.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Style controls the shared container and form appearance plus horoscope cards and Astrology Bot chat colors. Vedic result surfaces and tiles now live under Vedic Astrology → Appearance, where their no-request preview does not call the API.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Ephemeris Style controls the monthly table preview, grid, header, badge, today row, retrograde, ingress, and station colors.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Typography controls heading, body, table, input, button, and helper text sizes.', 'astrology-suite-freeastroapi'); ?></li>
                </ul>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI chart designer URL. */
                            __('Custom chart JSON can be generated in the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Chart Designer</a>. Paste chart_config only into the custom-config field, or paste a partial SVG/PNG chart request body into an individual theme body.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_CHART_DESIGNER_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: 1: Vedic visual chart docs URL, 2: full Vedic calculate docs URL. */
                            __('The Vedic blocks use the <a href="%1$s" target="_blank" rel="noopener">Vedic Visual Chart endpoint</a> and <a href="%2$s" target="_blank" rel="noopener">Full Vedic Calculate endpoint</a>.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_VEDIC_VISUAL_DOCS_URL),
                            esc_url(self::UPSTREAM_VEDIC_CALCULATE_DOCS_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI astrology bot URL. */
                            __('The astrology bot block is based on the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Astrology Bot</a> experience and runs inside this plugin through a WordPress REST proxy.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_ASTROLOGY_BOT_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI ephemeris docs URL. */
                            __('The ephemeris block uses the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Western Ephemeris endpoint</a> with table grid output for monthly planetary tables.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_EPHEMERIS_DOCS_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI daily sign docs URL. */
                            __('The daily horoscope block uses the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Daily Sign Horoscope endpoint</a>, which is suitable for free-plan daily engagement features.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_DAILY_SIGN_DOCS_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI daily personal docs URL. */
                            __('The personalized horoscope block uses the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI Daily Personal Horoscope endpoint</a> for birth-data-based daily timing and transit context.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_DAILY_PERSONAL_DOCS_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        sprintf(
                            /* translators: %s: FreeAstroAPI chart docs URL. */
                            __('The per-theme body editor follows the <a href="%s" target="_blank" rel="noopener">FreeAstroAPI SVG/PNG chart endpoint</a>: useful fields include size, theme_type, png_quality_scale, show_metadata, display_settings, fixed_stars, and chart_config.', 'astrology-suite-freeastroapi'),
                            esc_url(self::UPSTREAM_CHART_DOCS_URL)
                        ),
                        ['a' => ['href' => [], 'target' => [], 'rel' => []]]
                    );
                    ?>
                </p>

                <hr />
                <h2><?php esc_html_e('Troubleshooting and Production Checklist', 'astrology-suite-freeastroapi'); ?></h2>
                <ul>
                    <li><?php esc_html_e('If an admin chart preview is waiting, leave the page open while the site-wide request queue respects the saved plan limit. Reload only after it has had time to run.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('For city and timezone accuracy, type at least two characters and select a suggestion instead of leaving unmatched free text in the city field.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Use an exact birth time for Vedic calculations. Western natal forms can use the unknown-time option, which may omit houses and angles.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('After changing Report, Objects, Style, or Custom Chart settings, purge any WordPress page cache, host cache, and CDN cache serving the chart page. If those settings change often, exclude that page from full-page caching.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Confirm the public calculation setting and run one real frontend test while logged out before launching a page.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Keep API keys only in the plugin settings. Do not paste keys into blocks, shortcodes, page content, or custom CSS.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('The plugin spaces requests according to the saved RPS value, but FreeAstroAPI still enforces the plan’s daily or monthly quota.', 'astrology-suite-freeastroapi'); ?></li>
                </ul>

                <hr />
                <h2><?php esc_html_e('Legacy Block Migration', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('If your pages still use old freeastro/* or openastro/* blocks, run the migrator once to convert them to astrology-suite/*.', 'astrology-suite-freeastroapi'); ?></p>
                <p><?php esc_html_e('The old openastro/* block names and REST routes remain registered for compatibility, but new pages should use astrology-suite/*.', 'astrology-suite-freeastroapi'); ?></p>
                <p><a class="button button-secondary" href="<?php echo esc_url($migrate_url); ?>"><?php esc_html_e('Run Block Migrator', 'astrology-suite-freeastroapi'); ?></a></p>

                <hr />
                <h2><?php esc_html_e('City Autocomplete', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('The city field searches after at least two characters. Visitors can type a city name, or add a two-letter country filter such as "Paris, FR" or "Paris FR". Suggestions show city name, country, timezone, and coordinates when available.', 'astrology-suite-freeastroapi'); ?></p>
                <p><?php esc_html_e('Suggestions can be selected by clicking, or with Arrow Up, Arrow Down, Enter, and Escape from the keyboard.', 'astrology-suite-freeastroapi'); ?></p>

                <hr />
                <h2><?php esc_html_e('CSS Class Reference', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('Use these classes in your theme/customizer CSS to style Astrology Suite for FreeAstroAPI blocks.', 'astrology-suite-freeastroapi'); ?></p>
                <pre><code><?php echo esc_html(
"Container/Layout:
.wp-block-astrology-suite-widget
.astrology-suite-no-border
.astrology-suite-output

Form/Input Controls:
.astrology-suite-form-header
.astrology-suite-history-label
.astrology-suite-history-select
.astrology-suite-load-recent-wrap
.astrology-suite-load-recent-label
.astrology-suite-load-recent-select
.astrology-suite-load-recent-panel
.astrology-suite-form
.astrology-suite-input
.astrology-suite-city-field
.astrology-suite-city-input
.astrology-suite-city-loading
.astrology-suite-inline
.astrology-suite-btn
.astrology-suite-status
.astrology-suite-results
.astrology-suite-result-btn
.astrology-suite-result-main
.astrology-suite-result-primary
.astrology-suite-result-name
.astrology-suite-result-country
.astrology-suite-result-meta
.astrology-suite-result-meta-item

Report/Card Elements:
.astrology-suite-report-title
.astrology-suite-card
.astrology-suite-access-note
.astrology-suite-planet-item

Simple Sign Elements:
.astrology-suite-simple-sign-card
.astrology-suite-simple-sign-value
.astrology-suite-simple-sign-interpretation

Daily Horoscope Elements:
.astrology-suite-horoscope-form
.astrology-suite-horoscope-card
.astrology-suite-horoscope-theme-row
.astrology-suite-horoscope-theme
.astrology-suite-horoscope-keywords
.astrology-suite-horoscope-summary
.astrology-suite-horoscope-scores
.astrology-suite-horoscope-score-factors
.astrology-suite-horoscope-personal
.astrology-suite-horoscope-personal-meta
.astrology-suite-horoscope-transits
.astrology-suite-horoscope-transit-meta
.astrology-suite-horoscope-timing
.astrology-suite-horoscope-lucky
.astrology-suite-horoscope-guidance
.astrology-suite-horoscope-astro

Chart/Print Elements:
.astrology-suite-chart-card
.astrology-suite-chart
.astrology-suite-print-btn
.astrology-suite-astro-glyph

Ephemeris Elements:
.astrology-suite-ephemeris
.astrology-suite-ephemeris-toolbar
.astrology-suite-ephemeris-filter
.astrology-suite-ephemeris-chip
.astrology-suite-ephemeris-table-card
.astrology-suite-ephemeris-table-meta
.astrology-suite-ephemeris-row-badge
.astrology-suite-ephemeris-table
.astrology-suite-ephemeris-row
.astrology-suite-ephemeris-cell
.astrology-suite-ephemeris-glyph

Astrology Bot Elements:
.astrology-suite-bot
.astrology-suite-bot-phone
.astrology-suite-bot-header
.astrology-suite-bot-contacts
.astrology-suite-bot-contact
.astrology-suite-bot-profile
.astrology-suite-bot-messages
.astrology-suite-bot-message
.astrology-suite-bot-quick
.astrology-suite-bot-composer
.astrology-suite-bot-input
.astrology-suite-bot-send"
                ); ?></code></pre>
                <p><?php esc_html_e('Legacy .openastro-* classes and --openastro-* variables remain supported for existing custom CSS.', 'astrology-suite-freeastroapi'); ?></p>
                <p><a href="<?php echo esc_url($css_doc_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Open full CSS class docs (Markdown)', 'astrology-suite-freeastroapi'); ?></a></p>

                <hr />
                <h2><?php esc_html_e('REST Endpoints Used By The Blocks', 'astrology-suite-freeastroapi'); ?></h2>
                <ul>
                    <li><code>GET /wp-json/astrology-suite/v1/geo/search</code> - <?php esc_html_e('public city autocomplete proxy.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/natal/calculate</code> - <?php esc_html_e('natal data and report calculation. Used by report-only and chart-report modes.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/natal/chart-svg</code> - <?php esc_html_e('natal wheel SVG generation using the selected Custom Chart request body. Used by chart-only and chart-report modes.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/western/simple-signs</code> - <?php esc_html_e('simple sign calculation for Sun, Moon, Rising, or Midheaven modes.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/western/ephemeris</code> - <?php esc_html_e('monthly ephemeris grid calculation. Used by the ephemeris mode.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/western/daily-horoscope</code> - <?php esc_html_e('daily sign horoscope proxy. Used by the daily-horoscope mode.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/western/personalized-horoscope</code> - <?php esc_html_e('personalized daily horoscope proxy. Used by the personalized-horoscope mode.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/vedic/visual-chart</code> - <?php esc_html_e('sanitized V2 Vedic SVG chart proxy. Used by both Vedic modes.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/vedic/calculate</code> - <?php esc_html_e('full V2 Vedic calculation proxy. Used by the vedic-workbench mode.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><code>POST /wp-json/astrology-suite/v1/astrology-bot/chat</code> - <?php esc_html_e('astrology bot chat proxy. Used by the astrology-bot mode.', 'astrology-suite-freeastroapi'); ?></li>
                </ul>
                <p><?php esc_html_e('Calculation routes are available to public visitors when public calculations are enabled in the API tab. If disabled, they require a logged-in user who can edit posts. The matching /wp-json/openastro/v1/* routes remain available as legacy aliases.', 'astrology-suite-freeastroapi'); ?></p>

                <hr />
                <h2><?php esc_html_e('External Service Disclosure', 'astrology-suite-freeastroapi'); ?></h2>
                <p><?php esc_html_e('Astrology Suite connects to FreeAstroAPI to search cities and generate astrology calculations. The Astrology Bot also connects to Google Gemini to generate chat answers.', 'astrology-suite-freeastroapi'); ?></p>
                <ul>
                    <li><?php esc_html_e('City autocomplete sends the typed city query and optional country filter to FreeAstroAPI over HTTPS when a visitor types at least two characters into the city field.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Natal chart, report, chart SVG, simple-sign, ephemeris, horoscope, Vedic chart/workbench, and astrology bot requests send submitted astrology data and calculation options to FreeAstroAPI over HTTPS when a visitor submits the form while public calculations are enabled, or when an editor submits while public calculations are disabled.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Astrology bot requests may send the chat message, recent chat history, selected Western/Vedic system, and submitted birth profile to FreeAstroAPI MCP and Google Gemini over HTTPS.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('These calculation requests include the site’s configured FreeAstroAPI key in a server-to-server request header. The API key is not sent to the browser.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('Astrology bot answer requests include the site’s configured Gemini API key in a server-to-server request header. The Gemini API key is not sent to the browser.', 'astrology-suite-freeastroapi'); ?></li>
                    <li><?php esc_html_e('The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” list is stored only in the visitor’s local browser storage.', 'astrology-suite-freeastroapi'); ?></li>
                </ul>
                <p><?php esc_html_e('Public calculations can be turned off in the API tab. When disabled, visitors without editor permissions see an access note instead of the calculation form.', 'astrology-suite-freeastroapi'); ?></p>
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
                <p><?php esc_html_e('Astrology Suite for FreeAstroAPI saves the last 20 successful calculation inputs in the current browser only. Saved inputs are not written to WordPress.', 'astrology-suite-freeastroapi'); ?></p>
                <p><?php esc_html_e('Use the "Past inputs..." dropdown at the top of each widget form to reload a previous chart, report, or simple-sign request.', 'astrology-suite-freeastroapi'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    private static function count_legacy_block_posts(): int
    {
        $post_ids = [];
        foreach (['wp:freeastro/', 'wp:openastro/'] as $needle) {
            $query = new \WP_Query([
                'post_type' => 'any',
                'post_status' => 'any',
                's' => $needle,
                'posts_per_page' => -1,
                'no_found_rows' => true,
                'fields' => 'ids',
                'cache_results' => false,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ]);

            foreach ((array) $query->posts as $post_id) {
                $post_ids[(int) $post_id] = true;
            }
        }

        return count($post_ids);
    }

    public static function maybe_render_block_migration_notice(): void
    {
        if (!is_admin() || !current_user_can('edit_posts')) {
            return;
        }

        $migrated_param = filter_input(INPUT_GET, 'astrology_suite_migrated', FILTER_SANITIZE_NUMBER_INT);
        if ($migrated_param === null || $migrated_param === false || $migrated_param === '') {
            $migrated_param = filter_input(INPUT_GET, 'openastro_migrated', FILTER_SANITIZE_NUMBER_INT);
        }
        if ($migrated_param !== null && $migrated_param !== false && $migrated_param !== '') {
            $count = max(0, (int) $migrated_param);
            /* translators: %d: Number of posts migrated to astrology-suite blocks. */
            $migrated_message_format = __('Astrology Suite for FreeAstroAPI migrated %d post(s) to astrology-suite/* blocks.', 'astrology-suite-freeastroapi');
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
            esc_html__('Legacy freeastro/* or openastro/* blocks were found. Migrate them to astrology-suite/*.', 'astrology-suite-freeastroapi') .
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
            's' => 'wp:',
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
            if (
                strpos($content, 'wp:freeastro/') === false &&
                strpos($content, 'wp:openastro/') === false &&
                strpos($content, '/freeastro/') === false &&
                strpos($content, '/openastro/') === false &&
                strpos($content, 'wp-block-openastro-widget') === false &&
                strpos($content, 'data-openastro-widget') === false
            ) {
                continue;
            }

            $new_content = str_replace(
                [
                    'wp:freeastro/',
                    'wp:openastro/',
                    '/wp-json/freeastro/',
                    '/wp-json/openastro/',
                    '"/freeastro/',
                    '"/openastro/',
                    '\\/freeastro/',
                    '\\/openastro/',
                    'wp-block-openastro-widget',
                    'data-openastro-widget',
                ],
                [
                    'wp:astrology-suite/',
                    'wp:astrology-suite/',
                    '/wp-json/astrology-suite/',
                    '/wp-json/astrology-suite/',
                    '"/astrology-suite/',
                    '"/astrology-suite/',
                    '\\/astrology-suite/',
                    '\\/astrology-suite/',
                    'wp-block-astrology-suite-widget',
                    'data-astrology-suite-widget',
                ],
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

        $redirect = add_query_arg(
            ['page' => self::SETTINGS_PAGE_SLUG, 'astrology_suite_migrated' => (string) $updated],
            admin_url('options-general.php')
        );
        wp_safe_redirect($redirect);
        exit;
    }

    public static function register_routes(): void
    {
        foreach ([self::REST_NAMESPACE, self::LEGACY_REST_NAMESPACE] as $namespace) {
            register_rest_route($namespace, '/geo/search', [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [__CLASS__, 'geo_search'],
                'permission_callback' => '__return_true',
            ]);

            register_rest_route($namespace, '/natal/calculate', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'natal_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/natal/chart-svg', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'natal_chart_svg'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/western/simple-signs', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'simple_signs_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/western/ephemeris', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'ephemeris_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/western/daily-horoscope', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'daily_horoscope_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/western/personalized-horoscope', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'personalized_horoscope_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/vedic/visual-chart', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'vedic_visual_chart'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/vedic/calculate', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'vedic_calculate'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);

            register_rest_route($namespace, '/astrology-bot/chat', [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [__CLASS__, 'astrology_bot_chat'],
                'permission_callback' => [__CLASS__, 'calculation_permission'],
            ]);
        }
    }

    public static function add_rest_security_headers($response, $server, \WP_REST_Request $request)
    {
        if (!$response instanceof \WP_REST_Response) {
            return $response;
        }

        $route = $request->get_route();
        if (
            strpos($route, '/' . self::REST_NAMESPACE . '/') !== 0 &&
            strpos($route, '/' . self::LEGACY_REST_NAMESPACE . '/') !== 0
        ) {
            return $response;
        }

        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('X-Content-Type-Options', 'nosniff');

        return $response;
    }

    public static function calculation_permission()
    {
        if (!self::current_request_can_calculate()) {
            return new \WP_Error(
                'rest_forbidden',
                __('Astrology Suite public calculations are disabled. Log in with permission to edit posts, or enable public calculations in the API settings.', 'astrology-suite-freeastroapi'),
                ['status' => rest_authorization_required_code()]
            );
        }

        return true;
    }

    private static function ratelimit(string $bucket): bool
    {
        $fingerprint = self::client_fingerprint();
        $now = time();

        if (!self::consume_visitor_rate_limit($bucket, $fingerprint, self::VISITOR_RATE_LIMIT_MAX_REQUESTS, $now)) {
            return false;
        }

        return self::consume_visitor_rate_limit('global', $fingerprint, self::VISITOR_GLOBAL_RATE_LIMIT_MAX_REQUESTS, $now);
    }

    private static function consume_visitor_rate_limit(string $bucket, string $fingerprint, int $limit, int $now): bool
    {
        global $wpdb;

        $key = 'openastro_rl_' . substr(
            hash_hmac('sha256', $bucket . '|' . $fingerprint, wp_salt('auth')),
            0,
            32
        );

        for ($attempt = 0; $attempt < 5; $attempt++) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $stored = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
                    $key
                )
            );

            if ($stored === null) {
                $expires = $now + self::VISITOR_RATE_LIMIT_WINDOW_SECONDS;
                if (add_option($key, $expires . '|1', '', false)) {
                    return true;
                }
                continue;
            }

            $parts = explode('|', (string) $stored, 2);
            $expires = isset($parts[0]) && is_numeric($parts[0]) ? (int) $parts[0] : 0;
            $count = isset($parts[1]) && is_numeric($parts[1]) ? (int) $parts[1] : 0;

            if ($expires <= $now) {
                $expires = $now + self::VISITOR_RATE_LIMIT_WINDOW_SECONDS;
                $count = 0;
            }

            if ($count >= $limit) {
                return false;
            }

            $next_value = $expires . '|' . ($count + 1);
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$wpdb->options} SET option_value = %s WHERE option_name = %s AND option_value = %s",
                    $next_value,
                    $key,
                    (string) $stored
                )
            );

            if ($updated === 1) {
                wp_cache_delete($key, 'options');
                return true;
            }
        }

        return false;
    }

    private static function client_fingerprint(): string
    {
        $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
        if (!filter_var($remote_addr, FILTER_VALIDATE_IP)) {
            $remote_addr = 'unknown';
        }

        /**
         * Filters the trusted visitor IP used by the local abuse limiter.
         *
         * Hosts behind a trusted reverse proxy may return a validated proxy header here.
         * Never pass an unvalidated visitor-supplied forwarding header directly.
         *
         * @param string $remote_addr Validated REMOTE_ADDR or "unknown".
         */
        $filtered_ip = apply_filters('openastro_suite_client_ip', $remote_addr);
        $ip = is_string($filtered_ip) && filter_var($filtered_ip, FILTER_VALIDATE_IP) ? $filtered_ip : $remote_addr;

        return $ip;
    }

    private static function acquire_api_rate_slot()
    {
        global $wpdb;

        $limit = self::configured_api_rps_limit();
        $interval = 1 / $limit;

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $now = microtime(true);

            // A compare-and-swap update is required so concurrent PHP workers share one site-wide throttle.
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $stored = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
                    self::API_RATE_STATE_OPTION
                )
            );

            if ($stored === null) {
                $next_value = sprintf('%.6F', $now + $interval);
                if (add_option(self::API_RATE_STATE_OPTION, $next_value, '', false)) {
                    return true;
                }
                continue;
            }

            $next_request_at = is_numeric($stored) ? (float) $stored : 0.0;
            if ($next_request_at > $now) {
                $retry_after_ms = max(1, (int) ceil(($next_request_at - $now) * 1000));
                return new \WP_Error(
                    'api_plan_rate_limited',
                    __('The site is handling another FreeAstroAPI request. Retrying shortly is safe.', 'astrology-suite-freeastroapi'),
                    [
                        'status' => 429,
                        'rate_limit_type' => 'site_rps',
                        'retry_after' => max(1, (int) ceil($retry_after_ms / 1000)),
                        'retry_after_ms' => $retry_after_ms,
                        'configured_rps' => $limit,
                    ]
                );
            }

            $next_value = sprintf('%.6F', max($now, $next_request_at) + $interval);

            // WordPress has no atomic compare-and-swap option API; this prevents parallel requests from sharing a slot.
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$wpdb->options} SET option_value = %s WHERE option_name = %s AND option_value = %s",
                    $next_value,
                    self::API_RATE_STATE_OPTION,
                    (string) $stored
                )
            );

            if ($updated === 1) {
                wp_cache_delete(self::API_RATE_STATE_OPTION, 'options');
                return true;
            }
        }

        return new \WP_Error(
            'api_plan_rate_limited',
            __('FreeAstroAPI traffic is busy. Please retry in one second.', 'astrology-suite-freeastroapi'),
            [
                'status' => 429,
                'rate_limit_type' => 'site_rps',
                'retry_after' => 1,
                'retry_after_ms' => 1000,
                'configured_rps' => $limit,
            ]
        );
    }

    private static function acquire_api_rate_slot_for_preview()
    {
        $deadline = microtime(true) + self::ADMIN_PREVIEW_RATE_WAIT_SECONDS;

        do {
            $rate_slot = self::acquire_api_rate_slot();
            if (!is_wp_error($rate_slot)) {
                return true;
            }

            $data = $rate_slot->get_error_data();
            if (!is_array($data) || ($data['rate_limit_type'] ?? '') !== 'site_rps') {
                return $rate_slot;
            }

            $retry_after_ms = max(25, min(1500, (int) ($data['retry_after_ms'] ?? 1000)));
            $wait_ms = $retry_after_ms + wp_rand(20, 80);
            if (microtime(true) + ($wait_ms / 1000) > $deadline) {
                return $rate_slot;
            }

            usleep($wait_ms * 1000);
        } while (microtime(true) < $deadline);

        return new \WP_Error(
            'api_preview_rate_limited',
            __('Chart preview traffic is still busy. Reload this settings page in a few seconds.', 'astrology-suite-freeastroapi'),
            ['status' => 429, 'rate_limit_type' => 'site_rps']
        );
    }

    public static function geo_search(\WP_REST_Request $request)
    {
        if (!self::ratelimit('geo_search')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $q_param = $request->get_param('q');
        $limit_param = $request->get_param('limit');
        $country_param = $request->get_param('country');
        if (
            $q_param === null ||
            !is_scalar($q_param) ||
            ($limit_param !== null && !is_scalar($limit_param)) ||
            ($country_param !== null && !is_scalar($country_param))
        ) {
            return new \WP_Error('invalid_query', 'Query parameters must be scalar values.', ['status' => 400]);
        }

        $q = trim((string) $q_param);
        $limit = (int) ($limit_param ?: 10);
        $country = trim((string) $country_param);

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

        $chart_payload = self::apply_chart_body($payload, self::selected_chart_body(), 'svg', null, self::selected_chart_config());

        $result = self::request('POST', '/natal/chart/', $chart_payload, true);

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

    public static function ephemeris_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('ephemeris_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_ephemeris_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        return self::request('POST', '/ephemeris/calculate', $payload);
    }

    public static function daily_horoscope_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('daily_horoscope_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_daily_horoscope_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        $params = [
            'sign' => $payload['sign'],
            'date' => $payload['date'],
        ];
        if ($payload['tz_str'] !== '') {
            $params['tz_str'] = $payload['tz_str'];
        }

        return self::request('GET', '/horoscope/daily/sign?' . http_build_query($params), null, false, true, self::UPSTREAM_V2_BASE_URL);
    }

    public static function personalized_horoscope_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('personalized_horoscope_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_personalized_horoscope_payload($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        return self::request('POST', '/horoscope/daily/personal', $payload, false, true, self::UPSTREAM_V3_BASE_URL);
    }

    public static function vedic_visual_chart(\WP_REST_Request $request)
    {
        if (!self::ratelimit('vedic_visual_chart')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_vedic_payload($request->get_json_params(), true);
        if (is_wp_error($payload)) {
            return $payload;
        }

        $result = self::request('POST', '/vedic/visual/chart', $payload, true, true, self::UPSTREAM_V2_BASE_URL, 25);
        if (is_wp_error($result)) {
            return $result;
        }

        return ['svg' => $result];
    }

    public static function vedic_calculate(\WP_REST_Request $request)
    {
        if (!self::ratelimit('vedic_calculate')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $payload = self::sanitize_vedic_payload($request->get_json_params(), false);
        if (is_wp_error($payload)) {
            return $payload;
        }

        return self::request('POST', '/vedic/calculate', $payload, false, true, self::UPSTREAM_V2_BASE_URL, 30);
    }

    public static function astrology_bot_chat(\WP_REST_Request $request)
    {
        if (!self::ratelimit('astrology_bot_chat')) {
            return new \WP_Error('rate_limited', 'Too many requests.', ['status' => 429]);
        }

        $started = microtime(true);
        $payload = self::sanitize_astrology_bot_request($request->get_json_params());
        if (is_wp_error($payload)) {
            return $payload;
        }

        $plan = $payload['system'] === 'vedic'
            ? self::build_vedic_bot_plan($payload['message'], $payload['profile'])
            : self::build_western_bot_plan($payload['message'], $payload['profile'], $payload['language']);

        if (!empty($plan['needsBirthData'])) {
            return [
                'reply' => __('Share your birth date, exact birth time, and birth city first, then I can ground the answer in your chart.', 'astrology-suite-freeastroapi'),
                'usedTools' => [],
                'needsBirthData' => true,
                'latencyMs' => (int) round((microtime(true) - $started) * 1000),
            ];
        }

        $raw = null;
        if (!empty($plan['toolName']) && isset($plan['args']) && is_array($plan['args'])) {
            $raw = self::call_mcp_tool($payload['system'], (string) $plan['toolName'], $plan['args']);
            if (is_wp_error($raw)) {
                return $raw;
            }
        }

        $facts = $raw !== null ? self::compact_bot_facts(isset($plan['toolName']) ? (string) $plan['toolName'] : '', $raw) : '';
        $reply = self::generate_bot_reply([
            'system' => $payload['system'],
            'message' => $payload['message'],
            'history' => $payload['history'],
            'intent' => (string) ($plan['intent'] ?? 'general'),
            'toolName' => isset($plan['toolName']) ? (string) $plan['toolName'] : '',
            'facts' => $facts,
            'language' => $payload['language'],
        ]);
        if (is_wp_error($reply)) {
            return $reply;
        }

        return [
            'reply' => $reply,
            'usedTools' => !empty($plan['toolName']) ? [(string) $plan['toolName']] : [],
            'latencyMs' => (int) round((microtime(true) - $started) * 1000),
        ];
    }

    private static function payload_exceeds_size_limit(array $payload, int $limit = 65536): bool
    {
        $encoded = wp_json_encode($payload);
        return !is_string($encoded) || strlen($encoded) > $limit;
    }

    private static function payload_has_non_scalar_fields(array $payload, array $fields): bool
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $payload) && !is_scalar($payload[$field])) {
                return true;
            }
        }

        return false;
    }

    private static function is_valid_iso_date(string $date): bool
    {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches)) {
            return false;
        }

        $year = (int) $matches[1];
        $month = (int) $matches[2];
        $day = (int) $matches[3];

        return $year >= 1800 && $year <= 2100 && checkdate($month, $day, $year);
    }

    private static function sanitize_astrology_bot_request($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 50000)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (self::payload_has_non_scalar_fields($payload, ['system', 'message', 'language', 'lang'])) {
            return new \WP_Error('invalid_payload', 'Bot fields must be scalar values.', ['status' => 400]);
        }

        $system = isset($payload['system']) ? sanitize_key((string) $payload['system']) : 'western';
        if (!in_array($system, ['western', 'vedic'], true)) {
            $system = 'western';
        }

        $message = isset($payload['message']) ? trim(sanitize_textarea_field((string) $payload['message'])) : '';
        if ($message === '' || strlen($message) > 800) {
            return new \WP_Error('invalid_payload', 'Message must be 1-800 characters.', ['status' => 400]);
        }

        $history = [];
        if (isset($payload['history']) && is_array($payload['history'])) {
            foreach (array_slice($payload['history'], -8) as $item) {
                if (!is_array($item)) {
                    continue;
                }
                if (
                    (array_key_exists('role', $item) && !is_scalar($item['role'])) ||
                    (array_key_exists('content', $item) && !is_scalar($item['content']))
                ) {
                    continue;
                }
                $role = isset($item['role']) ? sanitize_key((string) $item['role']) : '';
                $content = isset($item['content']) ? trim(sanitize_textarea_field((string) $item['content'])) : '';
                if (!in_array($role, ['user', 'assistant'], true) || $content === '') {
                    continue;
                }
                $history[] = [
                    'role' => $role,
                    'content' => substr($content, 0, 1200),
                ];
            }
        }

        $profile = null;
        if (isset($payload['profile']) && is_array($payload['profile'])) {
            $profile = self::sanitize_astrology_bot_profile($payload['profile']);
            if (is_wp_error($profile)) {
                return $profile;
            }
        }

        return [
            'system' => $system,
            'message' => $message,
            'history' => $history,
            'profile' => $profile,
            'language' => self::resolve_natal_response_language(
                isset($payload['language']) ? (string) $payload['language'] : (isset($payload['lang']) ? (string) $payload['lang'] : null)
            ),
        ];
    }

    private static function sanitize_astrology_bot_profile(array $profile)
    {
        if (self::payload_has_non_scalar_fields($profile, ['birthDate', 'birthTime'])) {
            return new \WP_Error('invalid_payload', 'Profile date and time must be scalar values.', ['status' => 400]);
        }

        $birth_date = isset($profile['birthDate']) ? trim((string) $profile['birthDate']) : '';
        $birth_time = isset($profile['birthTime']) ? trim((string) $profile['birthTime']) : '';
        if (!self::is_valid_iso_date($birth_date)) {
            return new \WP_Error('invalid_payload', 'Profile birthDate must be YYYY-MM-DD.', ['status' => 400]);
        }
        if (!preg_match('/^\d{2}:\d{2}$/', $birth_time)) {
            return new \WP_Error('invalid_payload', 'Profile birthTime must be HH:MM.', ['status' => 400]);
        }

        [$year, $month, $day] = array_map('intval', explode('-', $birth_date));
        [$hour, $minute] = array_map('intval', explode(':', $birth_time));
        if (!checkdate($month, $day, $year) || $hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
            return new \WP_Error('invalid_payload', 'Profile birth date or time is out of range.', ['status' => 400]);
        }

        $city = isset($profile['city']) && is_array($profile['city']) ? $profile['city'] : [];
        if (self::payload_has_non_scalar_fields($city, ['name', 'timezone', 'tz_str', 'lat', 'lng', 'country_code', 'country_name', 'state', 'display_name'])) {
            return new \WP_Error('invalid_payload', 'Profile city fields must be scalar values.', ['status' => 400]);
        }
        $city_name = isset($city['name']) ? sanitize_text_field((string) $city['name']) : '';
        $timezone = isset($city['timezone']) ? sanitize_text_field((string) $city['timezone']) : (isset($city['tz_str']) ? sanitize_text_field((string) $city['tz_str']) : '');
        $lat = isset($city['lat']) ? (float) $city['lat'] : 999;
        $lng = isset($city['lng']) ? (float) $city['lng'] : 999;
        if (
            $city_name === '' ||
            $timezone === '' ||
            strlen($city_name) > 120 ||
            strlen($timezone) > 80 ||
            $lat < -90 ||
            $lat > 90 ||
            $lng < -180 ||
            $lng > 180
        ) {
            return new \WP_Error('invalid_payload', 'Profile city must include name, lat, lng, and timezone.', ['status' => 400]);
        }

        return [
            'birthDate' => $birth_date,
            'birthTime' => $birth_time,
            'city' => [
                'name' => $city_name,
                'lat' => $lat,
                'lng' => $lng,
                'timezone' => $timezone,
                'country_code' => isset($city['country_code']) ? sanitize_text_field((string) $city['country_code']) : '',
                'country_name' => isset($city['country_name']) ? sanitize_text_field((string) $city['country_name']) : '',
                'state' => isset($city['state']) ? sanitize_text_field((string) $city['state']) : '',
                'display_name' => isset($city['display_name']) ? sanitize_text_field((string) $city['display_name']) : '',
            ],
        ];
    }

    private static function classify_western_bot_message(string $message): string
    {
        $value = strtolower($message);
        if (preg_match('/\b(moon|lunar|new moon|full moon|waxing|waning|eclipse)\b/', $value)) return 'moon';
        if (preg_match('/\b(today|daily|tomorrow|right now|currently|forecast)\b/', $value)) return 'daily';
        if (preg_match('/\b(transit|saturn return|jupiter return|upcoming|this month|timing|when will)\b/', $value)) return 'transit';
        if (preg_match('/\b(sun sign|moon sign|rising sign|ascendant|what sign)\b/', $value)) return 'sign';
        if (preg_match('/\b(natal|birth chart|personality|purpose|career|love|houses?|aspects?|placements?)\b/', $value)) return 'natal';
        return 'general';
    }

    private static function split_bot_birth(array $profile): array
    {
        [$year, $month, $day] = array_map('intval', explode('-', $profile['birthDate']));
        [$hour, $minute] = array_map('intval', explode(':', $profile['birthTime']));
        return compact('year', 'month', 'day', 'hour', 'minute');
    }

    private static function western_bot_natal(array $profile, string $language): array
    {
        return array_merge(self::split_bot_birth($profile), [
            'time_known' => true,
            'city' => $profile['city']['name'],
            'lat' => $profile['city']['lat'],
            'lng' => $profile['city']['lng'],
            'tz_str' => $profile['city']['timezone'],
            'house_system' => self::western_house_system(),
            'zodiac_type' => 'tropical',
            'lang' => $language,
            'interpretation' => [
                'enable' => true,
                'style' => 'improved',
                'language' => $language,
            ],
        ]);
    }

    private static function vedic_bot_birth(array $profile): array
    {
        return array_merge(self::split_bot_birth($profile), [
            'city' => $profile['city']['name'],
            'lat' => $profile['city']['lat'],
            'lng' => $profile['city']['lng'],
            'tz_str' => $profile['city']['timezone'],
            'ayanamsha' => 'lahiri',
            'house_system' => 'whole_sign',
            'node_type' => 'mean',
        ]);
    }

    private static function current_bot_date_parts(): array
    {
        $now = current_datetime();
        return [
            'year' => (int) $now->format('Y'),
            'month' => (int) $now->format('n'),
            'date' => $now->format('Y-m-d'),
        ];
    }

    private static function build_western_bot_plan(string $message, ?array $profile, string $language): array
    {
        $intent = self::classify_western_bot_message($message);
        if ($intent === 'general') {
            return ['intent' => $intent, 'toolName' => '', 'args' => null];
        }

        $now = self::current_bot_date_parts();
        if ($intent === 'moon') {
            return [
                'intent' => $intent,
                'toolName' => 'v1_moon_month',
                'args' => [
                    'year' => $now['year'],
                    'month' => $now['month'],
                    'city' => $profile['city']['name'] ?? '',
                    'lat' => $profile['city']['lat'] ?? null,
                    'lon' => $profile['city']['lng'] ?? null,
                    'tz_str' => $profile['city']['timezone'] ?? 'UTC',
                    'include_zodiac' => true,
                    'include_rise_set' => false,
                    'include_visuals' => false,
                ],
            ];
        }

        if ($profile === null) {
            return ['intent' => $intent, 'toolName' => '', 'args' => null, 'needsBirthData' => true];
        }

        $natal = self::western_bot_natal($profile, $language);
        if ($intent === 'daily') {
            return [
                'intent' => $intent,
                'toolName' => 'v2_horoscope_daily_personal',
                'args' => [
                    'birth' => array_merge($natal, ['latitude' => $natal['lat'], 'longitude' => $natal['lng']]),
                    'date' => $now['date'],
                    'timezone' => $natal['tz_str'],
                    'tz_str' => $natal['tz_str'],
                    'locale' => $language,
                    'include_interpretation_blocks' => true,
                ],
            ];
        }

        if ($intent === 'transit') {
            return [
                'intent' => $intent,
                'toolName' => 'v1_western_transits_timeline',
                'args' => [
                    'natal' => $natal,
                    'year' => $now['year'],
                    'month' => $now['month'],
                    'include_houses' => true,
                ],
            ];
        }

        if ($intent === 'sign') {
            $tool = preg_match('/\b(rising|ascendant)\b/i', $message) ? 'v1_western_signs_rising' : (preg_match('/\bmoon\b/i', $message) ? 'v1_western_signs_moon' : 'v1_western_signs_sun');
            return ['intent' => $intent, 'toolName' => $tool, 'args' => $natal];
        }

        return [
            'intent' => $intent,
            'toolName' => 'v1_natal_calculate',
            'args' => [
                'include_minor' => false,
                'include_stelliums' => true,
                'body' => $natal,
            ],
        ];
    }

    private static function build_vedic_bot_plan(string $message, ?array $profile): array
    {
        if ($profile === null) {
            return ['intent' => 'natal', 'toolName' => '', 'args' => null, 'needsBirthData' => true];
        }

        $value = strtolower($message);
        $base = self::vedic_bot_birth($profile);
        if (preg_match('/\b(dasha|mahadasha|antar|vimshottari|period)\b/', $value)) {
            return ['intent' => 'natal', 'toolName' => 'v1_vedic_dasha', 'args' => array_merge($base, ['levels' => 2])];
        }

        if (preg_match('/\b(panchang|tithi|nakshatra|muhurta|today|daily)\b/', $value)) {
            return ['intent' => 'daily', 'toolName' => 'v1_vedic_panchang', 'args' => array_merge(self::split_bot_birth($profile), [
                'city' => $profile['city']['name'],
                'lat' => $profile['city']['lat'],
                'lng' => $profile['city']['lng'],
                'tz_str' => $profile['city']['timezone'],
            ])];
        }

        return [
            'intent' => 'natal',
            'toolName' => 'v1_vedic_calculate',
            'args' => array_merge($base, [
                'vargas' => [1, 9],
                'include_yogas' => true,
                'include_panchang' => false,
                'include_shadbala' => false,
                'include_ashtakavarga' => false,
                'dasha_levels' => 1,
            ]),
        ];
    }

    private static function call_mcp_tool(string $system, string $tool_name, array $args)
    {
        $api_key = (string) self::get_option_value(self::OPTION_API_KEY, '');
        if ($api_key === '') {
            return new \WP_Error('missing_api_key', 'FreeAstroAPI key is not configured.', ['status' => 500]);
        }

        $rate_slot = self::acquire_api_rate_slot();
        if (is_wp_error($rate_slot)) {
            return $rate_slot;
        }

        $response = wp_remote_post(add_query_arg('scope', $system === 'vedic' ? 'vedic-bot' : 'western-bot', self::UPSTREAM_MCP_URL), [
            'timeout' => 25,
            'redirection' => 0,
            'reject_unsafe_urls' => true,
            'sslverify' => true,
            'limit_response_size' => self::UPSTREAM_MAX_RESPONSE_BYTES,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
                'Idempotency-Key' => wp_generate_uuid4(),
            ],
            'body' => wp_json_encode([
                'jsonrpc' => '2.0',
                'id' => $system . '-wp-bot-' . wp_generate_uuid4(),
                'method' => 'tools/call',
                'params' => [
                    'name' => $tool_name,
                    'arguments' => $args,
                ],
            ]),
        ]);

        if (is_wp_error($response)) {
            return new \WP_Error('upstream_network_error', 'Could not reach FreeAstroAPI MCP.', ['status' => 502]);
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $raw = (string) wp_remote_retrieve_body($response);
        if (strlen($raw) > self::UPSTREAM_MAX_RESPONSE_BYTES) {
            return new \WP_Error('upstream_response_too_large', 'FreeAstroAPI MCP response exceeded the allowed size.', ['status' => 502]);
        }
        $decoded = json_decode($raw, true);
        if ($status < 200 || $status >= 300) {
            if ($status === 429) {
                return self::upstream_http_error($response, $status, $raw);
            }
            return new \WP_Error('upstream_error', self::upstream_status_message($status), ['status' => $status ?: 502]);
        }
        if (is_array($decoded) && isset($decoded['error'])) {
            return new \WP_Error('upstream_error', 'FreeAstroAPI MCP tool call failed.', ['status' => 502]);
        }
        if (!is_array($decoded)) {
            return new \WP_Error('invalid_response', 'Invalid JSON response from FreeAstroAPI MCP.', ['status' => 502]);
        }

        $content = $decoded['result']['content'] ?? [];
        if (is_array($content)) {
            foreach ($content as $item) {
                if (is_array($item) && ($item['type'] ?? '') === 'text' && isset($item['text'])) {
                    $text = (string) $item['text'];
                    $parsed = json_decode($text, true);
                    return $parsed !== null ? $parsed : $text;
                }
            }
        }

        return $decoded['result'] ?? [];
    }

    private static function compact_bot_facts(string $tool_name, $raw): string
    {
        if (!is_array($raw)) {
            return substr((string) $raw, 0, 1600);
        }

        if ($tool_name === 'v1_moon_month') {
            $days = [];
            foreach (array_slice(self::bot_array($raw['days'] ?? []), 0, 8) as $day) {
                $zodiac = self::bot_record($day['zodiac'] ?? []);
                $phase = self::bot_record($day['phase'] ?? []);
                $days[] = self::bot_text($day['calendar_date'] ?? '') . ': Moon in ' . self::bot_text($zodiac['sign'] ?? ($zodiac['sign_id'] ?? '')) . ', ' . self::bot_text($phase['name'] ?? '') . ', illumination ' . self::bot_text($phase['illumination'] ?? '');
            }
            return trim('Moon month ' . self::bot_text($raw['month_name'] ?? '') . ' ' . self::bot_text($raw['year'] ?? '') . '. Nearby days: ' . implode('; ', $days) . '.');
        }

        if (strpos($tool_name, 'vedic') !== false) {
            $planets = [];
            foreach (array_slice(self::bot_array($raw['planets'] ?? []), 0, 10) as $planet) {
                $planets[] = self::bot_text($planet['name'] ?? '') . ': ' . self::bot_text($planet['sign'] ?? '') . ', house ' . self::bot_text($planet['house'] ?? '') . ', ' . self::bot_text($planet['nakshatra'] ?? '');
            }
            $parts = [];
            if (!empty($raw['ascendant'])) $parts[] = 'Ascendant: ' . substr((string) wp_json_encode($raw['ascendant']), 0, 200) . '.';
            if ($planets !== []) $parts[] = 'Grahas: ' . implode('; ', $planets) . '.';
            if (!empty($raw['vimshottari_dasha'])) $parts[] = 'Dasha: ' . substr((string) wp_json_encode($raw['vimshottari_dasha']), 0, 900) . '.';
            if (!empty($raw['tithi']) || !empty($raw['nakshatra'])) $parts[] = 'Panchang: ' . substr((string) wp_json_encode($raw), 0, 900) . '.';
            return implode("\n", $parts);
        }

        $data = self::bot_record($raw['data'] ?? $raw);
        $personal = self::bot_record($data['personal'] ?? []);
        $astro = self::bot_record($data['astro'] ?? []);
        $content = self::bot_record($data['content'] ?? []);
        $planet_source = self::bot_array($raw['planets'] ?? ($data['planets'] ?? []));
        $planets = [];
        foreach (array_slice($planet_source, 0, 12) as $planet) {
            $planets[] = self::bot_text($planet['name'] ?? ($planet['id'] ?? '')) . ': ' . self::bot_text($planet['sign'] ?? ($planet['sign_id'] ?? '')) . ', house ' . self::bot_text($planet['house'] ?? '');
        }

        $parts = [];
        if ($planets !== []) $parts[] = 'Planets: ' . implode('; ', $planets) . '.';
        if (!empty($content['text'])) $parts[] = 'Daily text: ' . self::bot_text($content['text']);
        if (!empty($astro['moon_sign'])) $parts[] = 'Moon context: ' . substr((string) wp_json_encode($astro['moon_sign']), 0, 400) . '.';
        if (!empty($personal['transits_top'])) $parts[] = 'Top transits: ' . substr((string) wp_json_encode($personal['transits_top']), 0, 900) . '.';
        if ($parts === []) $parts[] = substr((string) wp_json_encode($raw), 0, 1600);
        return implode("\n", $parts);
    }

    private static function bot_record($value): array
    {
        return is_array($value) ? $value : [];
    }

    private static function bot_array($value): array
    {
        if (!is_array($value)) {
            return [];
        }
        $items = [];
        foreach ($value as $item) {
            if (is_array($item)) {
                $items[] = $item;
            }
        }
        return $items;
    }

    private static function bot_text($value): string
    {
        if (is_scalar($value) || $value === null) {
            return (string) $value;
        }
        return substr((string) wp_json_encode($value), 0, 240);
    }

    private static function bot_language_label(string $language): string
    {
        return [
            'en' => 'English',
            'fr' => 'French',
            'es' => 'Spanish',
            'tr' => 'Turkish',
            'ru' => 'Russian',
            'vi' => 'Vietnamese',
            'pt' => 'Portuguese',
        ][$language] ?? 'English';
    }

    private static function generate_bot_reply(array $input)
    {
        $history_lines = [];
        foreach ($input['history'] as $item) {
            $history_lines[] = $item['role'] . ': ' . $item['content'];
        }

        $language_label = self::bot_language_label((string) $input['language']);
        $prompt = implode("\n\n", array_filter([
            'You are a ' . ($input['system'] === 'vedic' ? 'Vedic' : 'Western') . ' astrology assistant.',
            'Answer directly in plain ' . $language_label . '. Do not mention JSON, MCP, tool names, WordPress, or implementation.',
            'Never invent chart facts. Use only the supplied facts for chart-specific claims.',
            $history_lines !== [] ? "Recent chat:\n" . implode("\n", $history_lines) : '',
            'User question: ' . $input['message'],
            $input['facts'] !== '' ? "Facts:\n" . $input['facts'] : '',
            'Intent: ' . $input['intent'] . ($input['toolName'] !== '' ? ', ' . $input['toolName'] : ''),
            'Return exactly JSON: {"answer":"..."}. Keep the answer under 180 words.',
        ]));

        if (function_exists('wp_ai_client_prompt') && function_exists('wp_supports_ai')) {
            if (!wp_supports_ai()) {
                return new \WP_Error(
                    'ai_disabled',
                    __('AI features are disabled on this WordPress site.', 'astrology-suite-freeastroapi'),
                    ['status' => 503]
                );
            }

            return self::generate_bot_reply_with_wordpress_ai($prompt);
        }

        $gemini_key = (string) self::get_option_value(self::OPTION_BOT_GEMINI_API_KEY, '');
        if ($gemini_key === '') {
            return new \WP_Error('missing_gemini_api_key', 'Gemini API key is not configured for the Astrology Bot.', ['status' => 500]);
        }

        $model = self::sanitize_bot_gemini_model((string) self::get_option_value(self::OPTION_BOT_GEMINI_MODEL, self::DEFAULT_GEMINI_MODEL));
        $response = wp_remote_post(self::GEMINI_API_BASE_URL . '/models/' . rawurlencode($model) . ':generateContent', [
            'timeout' => 30,
            'redirection' => 0,
            'reject_unsafe_urls' => true,
            'sslverify' => true,
            'limit_response_size' => self::UPSTREAM_MAX_RESPONSE_BYTES,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $gemini_key,
            ],
            'body' => wp_json_encode([
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 280,
                    'temperature' => 0.35,
                    'responseMimeType' => 'application/json',
                ],
            ]),
        ]);

        if (is_wp_error($response)) {
            return new \WP_Error('gemini_network_error', 'Could not reach Gemini for the Astrology Bot.', ['status' => 502]);
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $raw = (string) wp_remote_retrieve_body($response);
        if (strlen($raw) > self::UPSTREAM_MAX_RESPONSE_BYTES) {
            return new \WP_Error('gemini_response_too_large', 'Gemini response exceeded the allowed size.', ['status' => 502]);
        }
        $decoded = json_decode($raw, true);
        if ($status < 200 || $status >= 300) {
            if ($status === 401 || $status === 403) {
                $message = 'The Gemini API key was rejected.';
            } elseif ($status === 429) {
                $message = 'The Gemini API rate limit was reached.';
            } elseif ($status >= 500) {
                $message = 'The Gemini service is temporarily unavailable.';
            } else {
                $message = 'Gemini could not generate a reply.';
            }
            return new \WP_Error('gemini_error', $message, ['status' => $status ?: 502]);
        }
        if (!is_array($decoded)) {
            return new \WP_Error('invalid_response', 'Invalid JSON response from Gemini.', ['status' => 502]);
        }

        $output = '';
        $parts = $decoded['candidates'][0]['content']['parts'] ?? [];
        if (is_array($parts)) {
            foreach ($parts as $part) {
                if (is_array($part) && isset($part['text'])) {
                    $output .= (string) $part['text'];
                }
            }
        }
        $output = trim($output);
        if ($output === '') {
            return new \WP_Error('empty_response', 'Gemini returned an empty reply.', ['status' => 502]);
        }

        return self::parse_bot_reply_output($output);
    }

    private static function generate_bot_reply_with_wordpress_ai(string $prompt)
    {
        try {
            $builder = wp_ai_client_prompt($prompt)->using_provider('google');
            if (!$builder->is_supported_for_text_generation()) {
                return new \WP_Error(
                    'missing_ai_provider',
                    __('Connect Google under Settings > Connectors to enable the Astrology Bot.', 'astrology-suite-freeastroapi'),
                    ['status' => 503]
                );
            }

            $builder = $builder->as_json_response([
                'type' => 'object',
                'properties' => [
                    'answer' => ['type' => 'string'],
                ],
                'required' => ['answer'],
                'additionalProperties' => false,
            ]);
            $output = $builder->generate_text();
        } catch (\Throwable $error) {
            return new \WP_Error(
                'wordpress_ai_error',
                __('WordPress could not initialize the configured AI provider.', 'astrology-suite-freeastroapi'),
                ['status' => 503]
            );
        }

        if (is_wp_error($output)) {
            return new \WP_Error(
                'wordpress_ai_error',
                __('The configured WordPress AI provider could not generate an astrology reply.', 'astrology-suite-freeastroapi'),
                ['status' => 502]
            );
        }

        $output = trim((string) $output);
        if ($output === '') {
            return new \WP_Error('empty_response', 'The WordPress AI provider returned an empty reply.', ['status' => 502]);
        }

        return self::parse_bot_reply_output($output);
    }

    private static function parse_bot_reply_output(string $output)
    {
        $json_text = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $output);
        $parsed = json_decode(trim((string) $json_text), true);
        if (is_array($parsed) && isset($parsed['answer']) && trim((string) $parsed['answer']) !== '') {
            return trim((string) $parsed['answer']);
        }

        return $output;
    }

    private static function ephemeris_body_map(): array
    {
        return [
            'sun' => 'Sun',
            'moon' => 'Moon',
            'mercury' => 'Mercury',
            'venus' => 'Venus',
            'mars' => 'Mars',
            'jupiter' => 'Jupiter',
            'saturn' => 'Saturn',
            'uranus' => 'Uranus',
            'neptune' => 'Neptune',
            'pluto' => 'Pluto',
            'mean-node' => 'Mean Node',
            'mean_node' => 'Mean Node',
            'true-node' => 'True Node',
            'true_node' => 'True Node',
            'north-node' => 'True Node',
            'north_node' => 'True Node',
            'lilith' => 'Lilith',
            'true-lilith' => 'True Lilith',
            'true_lilith' => 'True Lilith',
            'chiron' => 'Chiron',
            'pholus' => 'Pholus',
            'ceres' => 'Ceres',
            'pallas' => 'Pallas',
            'juno' => 'Juno',
            'vesta' => 'Vesta',
            'ascendant' => 'Ascendant',
            'asc' => 'Ascendant',
            'mc' => 'MC',
            'midheaven' => 'MC',
            'vertex' => 'Vertex',
            'vx' => 'Vertex',
            'part-of-fortune' => 'Part of Fortune',
            'part_of_fortune' => 'Part of Fortune',
            'pof' => 'Part of Fortune',
        ];
    }

    private static function sanitize_ephemeris_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 16384)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (self::payload_has_non_scalar_fields($payload, ['year', 'month', 'zodiac_type', 'city', 'tz_str', 'lat', 'lng'])) {
            return new \WP_Error('invalid_payload', 'Ephemeris fields must be scalar values.', ['status' => 400]);
        }

        $year = isset($payload['year']) ? (int) $payload['year'] : 0;
        $month = isset($payload['month']) ? (int) $payload['month'] : 0;
        if ($year < 1800 || $year > 2100) {
            return new \WP_Error('invalid_payload', 'Year out of range.', ['status' => 400]);
        }
        if ($month < 1 || $month > 12) {
            return new \WP_Error('invalid_payload', 'Month out of range.', ['status' => 400]);
        }

        $body_map = self::ephemeris_body_map();
        $requested_bodies = isset($payload['bodies']) && is_array($payload['bodies']) ? $payload['bodies'] : [];
        if ($requested_bodies === []) {
            $requested_bodies = ['Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'True Node', 'Chiron'];
        }

        $bodies = [];
        foreach ($requested_bodies as $body) {
            if (!is_scalar($body)) {
                return new \WP_Error('invalid_payload', 'Ephemeris body names must be scalar values.', ['status' => 400]);
            }
            $key = sanitize_key(str_replace(' ', '-', (string) $body));
            if (!isset($body_map[$key])) {
                return new \WP_Error('invalid_payload', 'Unsupported ephemeris body requested.', ['status' => 400]);
            }
            $bodies[] = $body_map[$key];
        }
        $bodies = array_values(array_unique($bodies));
        if (count($bodies) < 1 || count($bodies) > 24) {
            return new \WP_Error('invalid_payload', 'Select between 1 and 24 ephemeris bodies.', ['status' => 400]);
        }

        $has_lat = isset($payload['lat']) && $payload['lat'] !== '';
        $has_lng = isset($payload['lng']) && $payload['lng'] !== '';
        $has_location = $has_lat || $has_lng;
        if ($has_location && !($has_lat && $has_lng)) {
            return new \WP_Error('invalid_payload', 'lat and lng must both be provided when selecting a location.', ['status' => 400]);
        }

        $lat = null;
        $lng = null;
        if ($has_location) {
            $lat = (float) $payload['lat'];
            $lng = (float) $payload['lng'];
            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
            }
        }

        $local_bodies = ['Ascendant', 'MC', 'Vertex', 'Part of Fortune'];
        if (!$has_location && array_intersect($local_bodies, $bodies) !== []) {
            return new \WP_Error('invalid_payload', 'Coordinate-based points require a selected city.', ['status' => 400]);
        }

        try {
            $start = new \DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $month), new \DateTimeZone('UTC'));
            $end = $start->modify('last day of this month');
        } catch (\Exception $e) {
            return new \WP_Error('invalid_payload', 'Invalid month.', ['status' => 400]);
        }

        $zodiac_type = isset($payload['zodiac_type']) ? sanitize_key((string) $payload['zodiac_type']) : 'tropical';
        if (!in_array($zodiac_type, ['tropical', 'sidereal'], true)) {
            $zodiac_type = 'tropical';
        }

        $api_payload = [
            'start' => $start->format('Y-m-d\T00:00:00\Z'),
            'end' => $end->format('Y-m-d\T00:00:00\Z'),
            'step' => '1d',
            'format' => 'table',
            'response_mode' => 'table',
            'table_style' => 'grid',
            'zodiac_type' => $zodiac_type,
            'bodies' => $bodies,
        ];

        if ($has_location) {
            $api_payload['lat'] = $lat;
            $api_payload['lng'] = $lng;
            $api_payload['city'] = isset($payload['city']) ? sanitize_text_field((string) $payload['city']) : '';
            $api_payload['tz_str'] = isset($payload['tz_str']) && (string) $payload['tz_str'] !== ''
                ? sanitize_text_field((string) $payload['tz_str'])
                : 'AUTO';
            $api_payload['include_angles'] = true;
            $api_payload['include_houses'] = true;
        }

        return $api_payload;
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
        if (self::payload_exceeds_size_limit($payload, 16384)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (
            self::payload_has_non_scalar_fields(
                $payload,
                ['year', 'month', 'day', 'hour', 'minute', 'name', 'city', 'lat', 'lng', 'tz_str', 'include_interpretation', 'interpretation_style', 'language', 'lang']
            )
        ) {
            return new \WP_Error('invalid_payload', 'Birth fields must be scalar values.', ['status' => 400]);
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
        if (!is_scalar($payload['include_points'][0])) {
            return new \WP_Error('invalid_payload', 'Point must be a scalar value.', ['status' => 400]);
        }

        $point = strtolower(trim((string) $payload['include_points'][0]));
        if ($point === 'midheaven') {
            $point = 'mc';
        }
        if (!in_array($point, ['sun', 'moon', 'rising', 'mc'], true)) {
            return new \WP_Error('invalid_payload', 'Point must be one of sun, moon, rising, mc/midheaven.', ['status' => 400]);
        }

        $year = (int) $payload['year'];
        $month = (int) $payload['month'];
        $day = (int) $payload['day'];
        $hour = (int) $payload['hour'];
        $minute = (int) $payload['minute'];

        if ($year < 1800 || $year > 2100) return new \WP_Error('invalid_payload', 'Year out of range.', ['status' => 400]);
        if (!checkdate($month, $day, $year)) return new \WP_Error('invalid_payload', 'Birth date is invalid.', ['status' => 400]);
        if ($hour < 0 || $hour > 23) return new \WP_Error('invalid_payload', 'Hour out of range.', ['status' => 400]);
        if ($minute < 0 || $minute > 59) return new \WP_Error('invalid_payload', 'Minute out of range.', ['status' => 400]);

        $city = sanitize_text_field((string) $payload['city']);
        if ($city === '' || strlen($city) > 120) {
            return new \WP_Error('invalid_payload', 'City is required.', ['status' => 400]);
        }

        $lat = null;
        $lng = null;
        if (isset($payload['lat']) || isset($payload['lng'])) {
            if (!isset($payload['lat'], $payload['lng'])) {
                return new \WP_Error('invalid_payload', 'lat and lng must both be provided when overriding coordinates.', ['status' => 400]);
            }
            $lat = (float) $payload['lat'];
            $lng = (float) $payload['lng'];
            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
            }
        }

        $name = isset($payload['name']) ? sanitize_text_field((string) $payload['name']) : '';
        $tz_str = isset($payload['tz_str']) ? sanitize_text_field((string) $payload['tz_str']) : 'AUTO';
        if (strlen($name) > 120 || strlen($tz_str) > 80) {
            return new \WP_Error('invalid_payload', 'Name or timezone is too long.', ['status' => 400]);
        }

        $include_interpretation = !isset($payload['include_interpretation']) || (bool) $payload['include_interpretation'];
        $style = isset($payload['interpretation_style']) ? sanitize_key((string) $payload['interpretation_style']) : 'improved';
        if (!in_array($style, ['improved', 'poetic'], true)) {
            return new \WP_Error('invalid_payload', 'interpretation_style must be improved or poetic.', ['status' => 400]);
        }

        $clean = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'name' => $name,
            'city' => $city,
            'tz_str' => $tz_str,
            'house_system' => self::western_house_system(),
            'zodiac_type' => 'tropical',
            'include_points' => [$point],
            'interpretation' => ['enable' => $include_interpretation],
        ];
        if ($lat !== null && $lng !== null) {
            $clean['lat'] = $lat;
            $clean['lng'] = $lng;
        }
        if ($include_interpretation) {
            $clean['interpretation']['style'] = $style;
        }

        return self::apply_natal_response_language($clean);
    }

    private static function sanitize_daily_horoscope_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 8192)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (self::payload_has_non_scalar_fields($payload, ['sign', 'date', 'tz_str'])) {
            return new \WP_Error('invalid_payload', 'Horoscope fields must be scalar values.', ['status' => 400]);
        }

        $sign = isset($payload['sign']) ? sanitize_key((string) $payload['sign']) : '';
        $allowed_signs = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'];
        if (!in_array($sign, $allowed_signs, true)) {
            return new \WP_Error('invalid_payload', 'Sign must be one of the 12 zodiac signs.', ['status' => 400]);
        }

        $date = isset($payload['date']) ? trim((string) $payload['date']) : 'today';
        if ($date === '') {
            $date = 'today';
        }
        if ($date !== 'today' && !self::is_valid_iso_date($date)) {
            return new \WP_Error('invalid_payload', 'Date must be today or YYYY-MM-DD.', ['status' => 400]);
        }

        $tz_str = isset($payload['tz_str']) ? sanitize_text_field((string) $payload['tz_str']) : '';
        if ($tz_str !== '' && strlen($tz_str) > 80) {
            return new \WP_Error('invalid_payload', 'Timezone is too long.', ['status' => 400]);
        }

        return [
            'sign' => $sign,
            'date' => $date,
            'tz_str' => $tz_str,
        ];
    }

    private static function sanitize_personalized_horoscope_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 20000)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (self::payload_has_non_scalar_fields($payload, ['date', 'tz_str', 'language', 'lang', 'include_interpretation_blocks'])) {
            return new \WP_Error('invalid_payload', 'Horoscope fields must be scalar values.', ['status' => 400]);
        }

        $birth = isset($payload['birth']) && is_array($payload['birth']) ? $payload['birth'] : $payload;
        if (
            self::payload_exceeds_size_limit($birth, 16000) ||
            self::payload_has_non_scalar_fields($birth, ['year', 'month', 'day', 'hour', 'minute', 'city', 'lat', 'lng', 'tz_str', 'time_known'])
        ) {
            return new \WP_Error('invalid_payload', 'Birth fields must be scalar values and within the size limit.', ['status' => 400]);
        }
        $time_known = true;
        if (array_key_exists('time_known', $birth)) {
            $parsed_time_known = filter_var($birth['time_known'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $time_known = $parsed_time_known === null ? true : $parsed_time_known;
        }

        foreach (['year', 'month', 'day', 'lat', 'lng'] as $field) {
            if (!isset($birth[$field])) {
                return new \WP_Error('invalid_payload', "Missing birth.{$field}.", ['status' => 400]);
            }
        }
        if ($time_known) {
            foreach (['hour', 'minute'] as $field) {
                if (!isset($birth[$field])) {
                    return new \WP_Error('invalid_payload', "Missing birth.{$field}.", ['status' => 400]);
                }
            }
        }

        $year = (int) $birth['year'];
        $month = (int) $birth['month'];
        $day = (int) $birth['day'];
        $hour = $time_known ? (int) $birth['hour'] : 12;
        $minute = $time_known ? (int) $birth['minute'] : 0;
        $lat = (float) $birth['lat'];
        $lng = (float) $birth['lng'];

        if ($year < 1800 || $year > 2100) return new \WP_Error('invalid_payload', 'Birth year out of range.', ['status' => 400]);
        if ($month < 1 || $month > 12) return new \WP_Error('invalid_payload', 'Birth month out of range.', ['status' => 400]);
        if (!checkdate($month, $day, $year)) return new \WP_Error('invalid_payload', 'Birth date is invalid.', ['status' => 400]);
        if ($time_known && ($hour < 0 || $hour > 23)) return new \WP_Error('invalid_payload', 'Birth hour out of range.', ['status' => 400]);
        if ($time_known && ($minute < 0 || $minute > 59)) return new \WP_Error('invalid_payload', 'Birth minute out of range.', ['status' => 400]);
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return new \WP_Error('invalid_payload', 'Invalid birth lat/lng.', ['status' => 400]);
        }

        $date = isset($payload['date']) ? trim((string) $payload['date']) : 'today';
        if ($date === '') {
            $date = 'today';
        }
        if ($date !== 'today' && !self::is_valid_iso_date($date)) {
            return new \WP_Error('invalid_payload', 'Date must be today or YYYY-MM-DD.', ['status' => 400]);
        }

        $tz_str = isset($payload['tz_str']) ? sanitize_text_field((string) $payload['tz_str']) : '';
        if ($tz_str === '' && isset($birth['tz_str'])) {
            $tz_str = sanitize_text_field((string) $birth['tz_str']);
        }
        if ($tz_str !== '' && strlen($tz_str) > 80) {
            return new \WP_Error('invalid_payload', 'Timezone is too long.', ['status' => 400]);
        }

        $city = isset($birth['city']) ? sanitize_text_field((string) $birth['city']) : '';
        $birth_tz = isset($birth['tz_str']) ? sanitize_text_field((string) $birth['tz_str']) : ($tz_str ?: 'AUTO');
        if (strlen($city) > 120 || strlen($birth_tz) > 80) {
            return new \WP_Error('invalid_payload', 'City or timezone is too long.', ['status' => 400]);
        }

        $body = [
            'birth' => [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'minute' => $minute,
                'city' => $city,
                'lat' => $lat,
                'lng' => $lng,
                'tz_str' => $birth_tz,
                'time_known' => $time_known,
            ],
            'date' => $date,
            'include_interpretation_blocks' => !isset($payload['include_interpretation_blocks']) || (bool) $payload['include_interpretation_blocks'],
        ];
        if ($tz_str !== '') {
            $body['tz_str'] = $tz_str;
        }

        $language = self::resolve_natal_response_language(
            isset($payload['language']) ? (string) $payload['language'] : (isset($payload['lang']) ? (string) $payload['lang'] : null)
        );
        $body['locale'] = $language;

        return $body;
    }

    private static function sanitize_vedic_payload($payload, bool $visual)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 20000)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (
            self::payload_has_non_scalar_fields(
                $payload,
                ['year', 'month', 'day', 'hour', 'minute', 'city', 'lat', 'lng', 'tz_str', 'ayanamsha', 'node_type', 'chart_style', 'theme_type', 'sign_label_mode', 'body_label_mode', 'include_outer_planets', 'size', 'dasha_levels', 'reference_date', 'include_avastha', 'include_yogas', 'include_panchang', 'include_shadbala', 'include_ashtakavarga']
            )
        ) {
            return new \WP_Error('invalid_payload', 'Vedic fields must be scalar values.', ['status' => 400]);
        }

        foreach (['year', 'month', 'day', 'hour', 'minute', 'lat', 'lng'] as $field) {
            if (!isset($payload[$field])) {
                return new \WP_Error('invalid_payload', "Missing {$field}.", ['status' => 400]);
            }
        }

        $year = (int) $payload['year'];
        $month = (int) $payload['month'];
        $day = (int) $payload['day'];
        $hour = (int) $payload['hour'];
        $minute = (int) $payload['minute'];
        $lat = (float) $payload['lat'];
        $lng = (float) $payload['lng'];

        if ($year < 1800 || $year > 2100 || !checkdate($month, $day, $year)) {
            return new \WP_Error('invalid_payload', 'Birth date is invalid or out of range.', ['status' => 400]);
        }
        if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
            return new \WP_Error('invalid_payload', 'Birth time is out of range.', ['status' => 400]);
        }
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
        }

        $city = isset($payload['city']) ? sanitize_text_field((string) $payload['city']) : '';
        $tz_str = isset($payload['tz_str']) ? sanitize_text_field((string) $payload['tz_str']) : 'AUTO';
        if ($tz_str === '') {
            $tz_str = 'AUTO';
        }
        if (strlen($city) > 120 || strlen($tz_str) > 80) {
            return new \WP_Error('invalid_payload', 'City or timezone is too long.', ['status' => 400]);
        }

        $visual_settings = self::vedic_chart_settings();
        $ayanamsha = isset($payload['ayanamsha']) ? sanitize_key((string) $payload['ayanamsha']) : 'lahiri';
        if ($ayanamsha === '' || strlen($ayanamsha) > 40) {
            $ayanamsha = 'lahiri';
        }
        $node_type = isset($payload['node_type']) ? sanitize_key((string) $payload['node_type']) : $visual_settings['node_type'];
        if (!in_array($node_type, ['mean', 'true'], true)) {
            return new \WP_Error('invalid_payload', 'node_type must be mean or true.', ['status' => 400]);
        }

        $base = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'tz_str' => $tz_str,
            'ayanamsha' => $ayanamsha,
            'house_system' => 'whole_sign',
            'node_type' => $node_type,
        ];

        $supported_divisions = $visual ? self::VEDIC_VISUAL_DIVISIONS : self::VEDIC_CALCULATION_DIVISIONS;
        $requested_divisions = $payload[$visual ? 'divisions' : 'vargas'] ?? [1, 9];
        if (!is_array($requested_divisions)) {
            $requested_divisions = [1, 9];
        }
        $divisions = [];
        foreach ($requested_divisions as $division) {
            if (!is_scalar($division)) {
                return new \WP_Error('invalid_payload', 'Vedic divisions must be scalar values.', ['status' => 400]);
            }
            $division = (int) $division;
            if (in_array($division, $supported_divisions, true) && !in_array($division, $divisions, true)) {
                $divisions[] = $division;
            }
        }
        if (!$divisions) {
            $divisions = [1];
        }

        if ($visual) {
            $chart_style = isset($payload['chart_style']) ? sanitize_key((string) $payload['chart_style']) : $visual_settings['chart_style'];
            if (!in_array($chart_style, ['north_indian', 'south_indian', 'east_indian'], true)) {
                $chart_style = $visual_settings['chart_style'];
            }
            $theme_type = isset($payload['theme_type']) ? sanitize_key((string) $payload['theme_type']) : $visual_settings['theme_type'];
            if (!in_array($theme_type, ['light', 'dark', 'mono'], true)) {
                $theme_type = $visual_settings['theme_type'];
            }
            $sign_label_mode = isset($payload['sign_label_mode']) ? sanitize_key((string) $payload['sign_label_mode']) : $visual_settings['sign_label_mode'];
            if (!in_array($sign_label_mode, ['number_abbr', 'number', 'abbr', 'astrofont', 'glyph', 'astrofont_number'], true)) {
                $sign_label_mode = $visual_settings['sign_label_mode'];
            }
            $body_label_mode = isset($payload['body_label_mode']) ? sanitize_key((string) $payload['body_label_mode']) : $visual_settings['body_label_mode'];
            if (!in_array($body_label_mode, ['abbr', 'glyph', 'astrofont', 'visual'], true)) {
                $body_label_mode = $visual_settings['body_label_mode'];
            }
            $include_outer_planets = array_key_exists('include_outer_planets', $payload)
                ? !empty($payload['include_outer_planets'])
                : !empty($visual_settings['include_outer_planets']);

            $result = array_merge($base, [
                'divisions' => array_slice($divisions, 0, 4),
                'chart_style' => $chart_style,
                'format' => 'svg',
                'size' => max(360, min(900, (int) ($payload['size'] ?? $visual_settings['size']))),
                'theme_type' => $theme_type,
                'body_types' => $include_outer_planets
                    ? ['ascendant', 'classical_grahas', 'nodes', 'outer_planets']
                    : ['ascendant', 'classical_grahas', 'nodes'],
                'display_settings' => self::vedic_chart_display_settings($visual_settings, $include_outer_planets),
                'chart_config' => self::vedic_chart_config_for_api($visual_settings, $sign_label_mode, $body_label_mode),
            ]);
            $custom_theme = self::vedic_custom_theme_for_api($visual_settings);
            if ($custom_theme !== []) {
                $result['custom_theme'] = $custom_theme;
            }

            return $result;
        }

        $dasha_levels = max(0, min(3, (int) ($payload['dasha_levels'] ?? 2)));
        $reference_date = isset($payload['reference_date']) ? trim((string) $payload['reference_date']) : '';
        if ($reference_date !== '' && !self::is_valid_iso_date($reference_date)) {
            return new \WP_Error('invalid_payload', 'reference_date must be YYYY-MM-DD.', ['status' => 400]);
        }

        $result = array_merge($base, [
            'vargas' => $divisions,
            'include_avastha' => !empty($payload['include_avastha']),
            'include_yogas' => !empty($payload['include_yogas']),
            'include_panchang' => !empty($payload['include_panchang']),
            'include_shadbala' => !empty($payload['include_shadbala']),
            'include_ashtakavarga' => !empty($payload['include_ashtakavarga']),
            'dasha_levels' => $dasha_levels,
        ]);
        if ($reference_date !== '') {
            $result['reference_date'] = $reference_date;
        }

        return $result;
    }

    private static function sanitize_payload($payload)
    {
        if (!is_array($payload)) {
            return new \WP_Error('invalid_payload', 'Payload must be an object.', ['status' => 400]);
        }
        if (self::payload_exceeds_size_limit($payload, 20000)) {
            return new \WP_Error('invalid_payload', 'Payload is too large.', ['status' => 413]);
        }
        if (
            self::payload_has_non_scalar_fields(
                $payload,
                ['year', 'month', 'day', 'hour', 'minute', 'name', 'time_known', 'city', 'lat', 'lng', 'tz_str', 'language', 'lang', 'include_minor_aspects']
            )
        ) {
            return new \WP_Error('invalid_payload', 'Birth fields must be scalar values.', ['status' => 400]);
        }

        $time_known = true;
        if (array_key_exists('time_known', $payload)) {
            $parsed_time_known = filter_var($payload['time_known'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $time_known = $parsed_time_known === null ? true : $parsed_time_known;
        }

        $required = ['year', 'month', 'day', 'city', 'lat', 'lng', 'tz_str'];
        if ($time_known) {
            $required[] = 'hour';
            $required[] = 'minute';
        }

        foreach ($required as $field) {
            if (!isset($payload[$field])) {
                return new \WP_Error('invalid_payload', "Missing {$field}.", ['status' => 400]);
            }
        }

        $year = (int) $payload['year'];
        $month = (int) $payload['month'];
        $day = (int) $payload['day'];
        $lat = (float) $payload['lat'];
        $lng = (float) $payload['lng'];
        $hour = null;
        $minute = null;
        if ($time_known) {
            $hour = (int) $payload['hour'];
            $minute = (int) $payload['minute'];
        }

        if ($year < 1800 || $year > 2100) return new \WP_Error('invalid_payload', 'Year out of range.', ['status' => 400]);
        if (!checkdate($month, $day, $year)) return new \WP_Error('invalid_payload', 'Birth date is invalid.', ['status' => 400]);
        if ($time_known && ($hour < 0 || $hour > 23)) return new \WP_Error('invalid_payload', 'Hour out of range.', ['status' => 400]);
        if ($time_known && ($minute < 0 || $minute > 59)) return new \WP_Error('invalid_payload', 'Minute out of range.', ['status' => 400]);
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return new \WP_Error('invalid_payload', 'Invalid lat/lng.', ['status' => 400]);
        }

        $name = isset($payload['name']) ? sanitize_text_field((string) $payload['name']) : '';
        $city = sanitize_text_field((string) $payload['city']);
        $tz_str = sanitize_text_field((string) $payload['tz_str']);
        if ($city === '' || $tz_str === '') {
            return new \WP_Error('invalid_payload', 'City and timezone are required.', ['status' => 400]);
        }
        if (strlen($name) > 120 || strlen($city) > 120 || strlen($tz_str) > 80) {
            return new \WP_Error('invalid_payload', 'Name, city, or timezone is too long.', ['status' => 400]);
        }

        $clean = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'name' => $name,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'tz_str' => $tz_str,
            'time_known' => $time_known,
            'house_system' => self::western_house_system(),
            'zodiac_type' => 'tropical',
            'include_speed' => true,
            'include_dominants' => true,
            'include_features' => self::get_enabled_features(),
            'interpretation' => ['enable' => true, 'style' => 'improved'],
            'include_minor_aspects' => !isset($payload['include_minor_aspects']) || (bool) $payload['include_minor_aspects'],
        ];
        if ($time_known) {
            $clean['hour'] = $hour;
            $clean['minute'] = $minute;
        }

        return self::apply_natal_response_language($clean);
    }

    private static function request(string $method, string $path, ?array $body = null, bool $expect_svg = false, bool $include_api_key = true, ?string $base_url = null, int $timeout = 15, bool $wait_for_preview_slot = false)
    {
        $method = strtoupper($method);
        $response_limit = $expect_svg ? self::SVG_MAX_RESPONSE_BYTES : self::UPSTREAM_MAX_RESPONSE_BYTES;
        $args = [
            'method' => $method,
            'timeout' => max(5, min(45, $timeout)),
            'redirection' => 0,
            'reject_unsafe_urls' => true,
            'sslverify' => true,
            'limit_response_size' => $response_limit,
            'headers' => [
                'Accept' => $expect_svg ? 'image/svg+xml, application/xml;q=0.9, text/xml;q=0.8' : 'application/json',
            ],
        ];

        if ($include_api_key) {
            $api_key = (string) self::get_option_value(self::OPTION_API_KEY, '');
            if ($api_key === '') {
                return new \WP_Error('missing_api_key', 'FreeAstroAPI key is not configured.', ['status' => 500]);
            }

            $rate_slot = $wait_for_preview_slot
                ? self::acquire_api_rate_slot_for_preview()
                : self::acquire_api_rate_slot();
            if (is_wp_error($rate_slot)) {
                return $rate_slot;
            }

            $args['headers']['x-api-key'] = $api_key;
            if ($method === 'POST') {
                $args['headers']['Idempotency-Key'] = wp_generate_uuid4();
            }
        }

        if ($body !== null) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = wp_json_encode($body);
        }

        $response = wp_remote_request(($base_url ?: self::UPSTREAM_BASE_URL) . $path, $args);
        if (is_wp_error($response)) {
            return new \WP_Error('upstream_network_error', 'Could not reach upstream API.', ['status' => 502]);
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $raw = (string) wp_remote_retrieve_body($response);
        if (strlen($raw) > $response_limit) {
            return new \WP_Error('upstream_response_too_large', 'Upstream response exceeded the allowed size.', ['status' => 502]);
        }

        if ($status < 200 || $status >= 300) {
            return self::upstream_http_error($response, $status, $raw);
        }

        if ($expect_svg) {
            $svg = self::sanitize_svg_response($raw);
            if ($svg === '') {
                return new \WP_Error('invalid_svg', 'Invalid SVG response from upstream.', ['status' => 502]);
            }
            return $svg;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return new \WP_Error('invalid_response', 'Invalid JSON response from upstream.', ['status' => 502]);
        }

        return $decoded;
    }

    private static function upstream_http_error(array $response, int $status, string $raw): \WP_Error
    {
        $data = ['status' => $status ?: 502];
        if ($status !== 429) {
            return new \WP_Error('upstream_error', self::upstream_status_message($status), $data);
        }

        $decoded = json_decode($raw, true);
        $decoded = is_array($decoded) ? $decoded : [];
        $error_value = $decoded['error'] ?? ($decoded['code'] ?? '');
        $error_code = is_array($error_value)
            ? sanitize_key((string) ($error_value['code'] ?? ($error_value['type'] ?? '')))
            : sanitize_key((string) $error_value);

        $retry_after_header = (string) wp_remote_retrieve_header($response, 'retry-after');
        $retry_after_ms = isset($decoded['retry_after_ms']) ? max(0, (int) $decoded['retry_after_ms']) : 0;
        if ($retry_after_ms === 0 && is_numeric($retry_after_header)) {
            $retry_after_ms = max(0, (int) ceil(((float) $retry_after_header) * 1000));
        }

        $data['rate_limit_type'] = 'upstream';
        if ($retry_after_ms > 0) {
            $data['retry_after_ms'] = $retry_after_ms;
            $data['retry_after'] = max(1, (int) ceil($retry_after_ms / 1000));
        }
        if (isset($decoded['tier_rps_limit'])) {
            $data['tier_rps_limit'] = max(1, (int) $decoded['tier_rps_limit']);
        }

        if ($error_code === 'rps_limit_exceeded') {
            $data['rate_limit_type'] = 'rps';
            return new \WP_Error(
                'upstream_rate_limited',
                __('The FreeAstroAPI plan throughput limit was reached. Retrying shortly is safe.', 'astrology-suite-freeastroapi'),
                $data
            );
        }

        if ($error_code === 'abuse_penalty_active') {
            $data['rate_limit_type'] = 'penalty';
            return new \WP_Error(
                'upstream_rate_limited',
                __('FreeAstroAPI temporarily paused requests after a burst. Please wait before trying again.', 'astrology-suite-freeastroapi'),
                $data
            );
        }

        if ($error_code === 'rate_limit_exceeded') {
            $data['rate_limit_type'] = 'quota';
            return new \WP_Error(
                'upstream_rate_limited',
                __('The FreeAstroAPI plan quota has been reached. Check the API dashboard or plan limits.', 'astrology-suite-freeastroapi'),
                $data
            );
        }

        return new \WP_Error('upstream_rate_limited', __('FreeAstroAPI rate limit reached.', 'astrology-suite-freeastroapi'), $data);
    }

    private static function sanitize_svg_response(string $raw): string
    {
        $raw = trim($raw);
        if (
            $raw === '' ||
            strlen($raw) > self::SVG_MAX_RESPONSE_BYTES ||
            stripos($raw, '<svg') === false ||
            preg_match('/<!\s*(?:DOCTYPE|ENTITY)\b/i', $raw)
        ) {
            return '';
        }

        if (!class_exists('\DOMDocument')) {
            return self::sanitize_svg_response_with_kses($raw);
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $loaded = $dom->loadXML($raw, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$loaded || !$dom->documentElement || strtolower($dom->documentElement->localName) !== 'svg') {
            return '';
        }

        $node_count = 0;
        self::sanitize_svg_node($dom->documentElement, 0, $node_count);
        $svg = $dom->saveXML($dom->documentElement);

        return is_string($svg) ? trim($svg) : '';
    }

    private static function sanitize_svg_node(\DOMNode $node, int $depth, int &$node_count): void
    {
        $node_count++;
        if ($depth > self::SVG_MAX_DEPTH || $node_count > self::SVG_MAX_NODES) {
            if ($node->parentNode) {
                $node->parentNode->removeChild($node);
            }
            return;
        }

        if ($node->nodeType === XML_COMMENT_NODE || $node->nodeType === XML_PI_NODE) {
            if ($node->parentNode) {
                $node->parentNode->removeChild($node);
            }
            return;
        }

        if ($node->nodeType === XML_ELEMENT_NODE) {
            $blocked_elements = [
                'script',
                'foreignobject',
                'iframe',
                'object',
                'embed',
                'audio',
                'video',
                'canvas',
                'link',
                'meta',
                'style',
                'a',
                'animate',
                'animatemotion',
                'animatetransform',
                'button',
                'discard',
                'feimage',
                'form',
                'handler',
                'image',
                'input',
                'listener',
                'option',
                'select',
                'set',
                'textarea',
            ];
            if (in_array(strtolower($node->localName), $blocked_elements, true)) {
                if ($node->parentNode) {
                    $node->parentNode->removeChild($node);
                }
                return;
            }

            if ($node->hasAttributes()) {
                $remove_attributes = [];
                foreach ($node->attributes as $attribute) {
                    $name = strtolower($attribute->nodeName);
                    $value = trim(html_entity_decode((string) $attribute->nodeValue, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                    if (strpos($name, 'on') === 0) {
                        $remove_attributes[] = $attribute->nodeName;
                        continue;
                    }

                    if (in_array($name, ['href', 'xlink:href', 'src'], true) && strpos($value, '#') !== 0) {
                        $remove_attributes[] = $attribute->nodeName;
                        continue;
                    }

                    if ($name === 'xml:base') {
                        $remove_attributes[] = $attribute->nodeName;
                        continue;
                    }

                    if (preg_match('/^\s*(?:javascript|vbscript|data):/i', $value)) {
                        $remove_attributes[] = $attribute->nodeName;
                        continue;
                    }

                    if ($name === 'style') {
                        $safe_style = safecss_filter_attr($value);
                        if (!is_string($safe_style) || trim($safe_style) === '') {
                            $remove_attributes[] = $attribute->nodeName;
                            continue;
                        }
                        $attribute->nodeValue = $safe_style;
                        $value = $safe_style;
                    }

                    if (preg_match('/url\s*\(/i', $value)) {
                        $without_local_references = preg_replace('/url\s*\(\s*([\'"]?)#[A-Za-z0-9_.:-]+\1\s*\)/i', '', $value);
                        if (!is_string($without_local_references) || preg_match('/url\s*\(/i', $without_local_references)) {
                            $remove_attributes[] = $attribute->nodeName;
                            continue;
                        }
                    }

                    if ($name === 'style' && preg_match('/(?:expression\s*\(|@import|behavior\s*:|-moz-binding)/i', $value)) {
                        $remove_attributes[] = $attribute->nodeName;
                    }
                }

                foreach ($remove_attributes as $attribute_name) {
                    $node->removeAttribute($attribute_name);
                }
            }
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            self::sanitize_svg_node($child, $depth + 1, $node_count);
        }
    }

    private static function sanitize_svg_response_with_kses(string $raw): string
    {
        if (
            strlen($raw) > self::SVG_MAX_RESPONSE_BYTES ||
            preg_match('/<!\s*(?:DOCTYPE|ENTITY)\b/i', $raw)
        ) {
            return '';
        }

        $allowed_svg = [
            'svg' => ['xmlns' => true, 'viewbox' => true, 'viewBox' => true, 'width' => true, 'height' => true, 'role' => true, 'aria-label' => true],
            'g' => ['id' => true, 'class' => true, 'transform' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'path' => ['d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'opacity' => true, 'transform' => true],
            'circle' => ['cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'ellipse' => ['cx' => true, 'cy' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'line' => ['x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'polyline' => ['points' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'polygon' => ['points' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'rect' => ['x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'opacity' => true],
            'text' => ['x' => true, 'y' => true, 'fill' => true, 'font-size' => true, 'font-family' => true, 'font-weight' => true, 'text-anchor' => true, 'dominant-baseline' => true, 'transform' => true, 'opacity' => true],
            'tspan' => ['x' => true, 'y' => true, 'dx' => true, 'dy' => true, 'fill' => true, 'font-size' => true, 'font-family' => true, 'font-weight' => true, 'text-anchor' => true],
            'defs' => [],
            'clipPath' => ['id' => true],
            'mask' => ['id' => true],
            'linearGradient' => ['id' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true],
            'radialGradient' => ['id' => true, 'cx' => true, 'cy' => true, 'r' => true],
            'stop' => ['offset' => true, 'stop-color' => true, 'stop-opacity' => true],
            'title' => [],
            'desc' => [],
        ];

        $svg = trim(wp_kses($raw, $allowed_svg));
        return stripos($svg, '<svg') !== false ? $svg : '';
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

    public static function register_shortcodes(): void
    {
        foreach (self::shortcode_names() as $shortcode) {
            add_shortcode($shortcode, [__CLASS__, 'render_shortcode']);
        }
    }

    public static function render_shortcode($atts = [], $content = null, string $tag = ''): string
    {
        $raw_atts = is_array($atts) ? array_change_key_case($atts, CASE_LOWER) : [];
        $defaults = [
            'mode' => '',
            'type' => '',
            'include_interpretation' => '',
            'includeinterpretation' => '',
            'interpretation_style' => '',
            'interpretationstyle' => '',
            'language' => '',
            'lang' => '',
            'class' => '',
            'preset' => '',
            'layout' => '',
            'divisions' => '',
            'modules' => '',
        ];
        $parsed = shortcode_atts($defaults, $raw_atts, $tag);
        $mode = self::shortcode_mode_from_tag($tag, (string) ($parsed['mode'] ?: $parsed['type']));
        $include_interpretation = $parsed['include_interpretation'] !== ''
            ? $parsed['include_interpretation']
            : $parsed['includeinterpretation'];
        $interpretation_style = $parsed['interpretation_style'] !== ''
            ? $parsed['interpretation_style']
            : $parsed['interpretationstyle'];
        $language = $parsed['language'] !== '' ? $parsed['language'] : $parsed['lang'];

        self::enqueue_widget_assets();

        return self::render_widget_container($mode, [
            'include_interpretation' => self::shortcode_bool($include_interpretation, true),
            'interpretation_style' => self::normalize_interpretation_style((string) $interpretation_style),
            'language' => self::normalize_frontend_language((string) $language),
            'class' => (string) $parsed['class'],
            'vedic_preset' => (string) $parsed['preset'],
            'vedic_layout' => (string) $parsed['layout'],
            'vedic_divisions' => (string) $parsed['divisions'],
            'vedic_modules' => (string) $parsed['modules'],
        ]);
    }

    public static function maybe_enqueue_shortcode_assets(): void
    {
        if (is_admin()) {
            return;
        }

        if (self::current_request_has_shortcode()) {
            self::enqueue_widget_assets();
        }
    }

    public static function maybe_print_late_shortcode_styles(): void
    {
        if (wp_style_is('openastro-suite-widgets-style', 'enqueued') && !wp_style_is('openastro-suite-widgets-style', 'done')) {
            wp_print_styles('openastro-suite-widgets-style');
        }
    }

    private static function frontend_config(): array
    {
        $vedic_settings = self::vedic_chart_settings();
        $vedic_style = self::vedic_style_defaults();

        return [
            'root' => esc_url_raw(rest_url(self::REST_NAMESPACE . '/')),
            'nonce' => wp_create_nonce('wp_rest'),
            'language' => self::normalize_frontend_language((string) self::get_option_value(self::OPTION_FRONTEND_LANGUAGE, 'auto')),
            'permissions' => [
                'canCalculate' => self::current_request_can_calculate(),
                'calculationMessage' => self::frontend_access_message(),
            ],
            'report' => [
                'detailLevel' => (string) self::get_option_value(self::OPTION_REPORT_DETAIL, 'full'),
                'maxPlanets' => (int) self::get_option_value(self::OPTION_REPORT_MAX_PLANETS, 13),
                'maxAspects' => (int) self::get_option_value(self::OPTION_REPORT_MAX_ASPECTS, 40),
                'showTables' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_TABLES, true),
                'showAngles' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_ANGLES, true),
                'showBirthContext' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_BIRTH_CONTEXT, true),
                'showAspectLinks' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_PERSONALIZED_NOTES, true),
                'showAspectGlyphs' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_ASPECT_GLYPHS, true),
                'showThematicInterpretation' => (bool) self::get_option_value(self::OPTION_REPORT_SHOW_THEMATIC_INTERPRETATION, false),
                'showPrintButton' => (bool) self::get_option_value(self::OPTION_CHART_SHOW_PRINT_BUTTON, true),
            ],
            'objects' => [
                'asc' => (bool) self::get_option_value(self::OPTION_OBJ_ASC, true),
                'mc' => (bool) self::get_option_value(self::OPTION_OBJ_MC, true),
                'chiron' => (bool) self::get_option_value(self::OPTION_OBJ_CHIRON, true),
                'lilith' => (bool) self::get_option_value(self::OPTION_OBJ_LILITH, true),
                'true_node' => self::western_node_type() === 'true',
                'mean_node' => self::western_node_type() === 'mean',
                'part_of_fortune' => (bool) self::get_option_value(self::OPTION_OBJ_PART_OF_FORTUNE, true),
            ],
            'vedic' => [
                'preset' => $vedic_settings['default_preset'],
                'chartStyle' => $vedic_settings['chart_style'],
                'themeType' => $vedic_settings['theme_type'],
                'nodeType' => $vedic_settings['node_type'],
                'ayanamsha' => $vedic_settings['ayanamsha'],
                'signLabelMode' => $vedic_settings['sign_label_mode'],
                'bodyLabelMode' => $vedic_settings['body_label_mode'],
                'size' => (int) $vedic_settings['size'],
                'includeOuterPlanets' => !empty($vedic_settings['include_outer_planets']),
                'defaultDivisions' => array_values(array_map('intval', (array) $vedic_settings['default_divisions'])),
                'dashaLevels' => (int) $vedic_settings['dasha_levels'],
                'modules' => [
                    'yogas' => !empty($vedic_settings['include_yogas']),
                    'panchang' => !empty($vedic_settings['include_panchang']),
                    'shadbala' => !empty($vedic_settings['include_shadbala']),
                    'ashtakavarga' => !empty($vedic_settings['include_ashtakavarga']),
                    'avastha' => !empty($vedic_settings['include_avastha']),
                ],
                'calculationDivisions' => self::VEDIC_CALCULATION_DIVISIONS,
                'visualDivisions' => self::VEDIC_VISUAL_DIVISIONS,
            ],
            'vedicStyle' => [
                'surfaceBgColor' => (string) self::get_option_value(self::OPTION_VEDIC_SURFACE_BG_COLOR, $vedic_style[self::OPTION_VEDIC_SURFACE_BG_COLOR]),
                'surfaceTextColor' => (string) self::get_option_value(self::OPTION_VEDIC_SURFACE_TEXT_COLOR, $vedic_style[self::OPTION_VEDIC_SURFACE_TEXT_COLOR]),
                'surfaceBorderColor' => (string) self::get_option_value(self::OPTION_VEDIC_SURFACE_BORDER_COLOR, $vedic_style[self::OPTION_VEDIC_SURFACE_BORDER_COLOR]),
                'accentColor' => (string) self::get_option_value(self::OPTION_VEDIC_ACCENT_COLOR, $vedic_style[self::OPTION_VEDIC_ACCENT_COLOR]),
                'tileBgColor' => (string) self::get_option_value(self::OPTION_VEDIC_TILE_BG_COLOR, $vedic_style[self::OPTION_VEDIC_TILE_BG_COLOR]),
                'tileTextColor' => (string) self::get_option_value(self::OPTION_VEDIC_TILE_TEXT_COLOR, $vedic_style[self::OPTION_VEDIC_TILE_TEXT_COLOR]),
                'tileBorderColor' => (string) self::get_option_value(self::OPTION_VEDIC_TILE_BORDER_COLOR, $vedic_style[self::OPTION_VEDIC_TILE_BORDER_COLOR]),
                'tableHeaderBgColor' => (string) self::get_option_value(self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR, $vedic_style[self::OPTION_VEDIC_TABLE_HEADER_BG_COLOR]),
                'tableHeaderTextColor' => (string) self::get_option_value(self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR, $vedic_style[self::OPTION_VEDIC_TABLE_HEADER_TEXT_COLOR]),
                'badgeBgColor' => (string) self::get_option_value(self::OPTION_VEDIC_BADGE_BG_COLOR, $vedic_style[self::OPTION_VEDIC_BADGE_BG_COLOR]),
                'badgeTextColor' => (string) self::get_option_value(self::OPTION_VEDIC_BADGE_TEXT_COLOR, $vedic_style[self::OPTION_VEDIC_BADGE_TEXT_COLOR]),
            ],
            'style' => [
                'showBorder' => (bool) self::get_option_value(self::OPTION_STYLE_SHOW_BORDER, true),
                'backgroundColor' => (string) self::get_option_value(self::OPTION_STYLE_BG_COLOR, '#ffffff'),
                'transparentBackground' => (bool) self::get_option_value(self::OPTION_STYLE_TRANSPARENT_BG, false),
                'textColor' => (string) self::get_option_value(self::OPTION_STYLE_TEXT_COLOR, '#111111'),
                'showHistory' => (bool) self::get_option_value(self::OPTION_STYLE_SHOW_HISTORY, true),
                'historyBgColor' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BG_COLOR, '#f9fafb'),
                'historyBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_BORDER_COLOR, '#e5e7eb'),
                'historyTextColor' => (string) self::get_option_value(self::OPTION_STYLE_HISTORY_TEXT_COLOR, '#111111'),
                'inputBorderRadius' => (int) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_RADIUS, 6),
                'inputBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BORDER_COLOR, '#d1d5db'),
                'inputBgColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_BG_COLOR, '#ffffff'),
                'inputTextColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_TEXT_COLOR, '#111111'),
                'inputPlaceholderColor' => (string) self::get_option_value(self::OPTION_STYLE_INPUT_PLACEHOLDER_COLOR, '#6b7280'),
                'buttonBorderRadius' => (int) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_RADIUS, 6),
                'buttonBgColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BG_COLOR, '#111827'),
                'buttonTextColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_TEXT_COLOR, '#ffffff'),
                'buttonBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_BUTTON_BORDER_COLOR, '#111827'),
                'labelColor' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_COLOR, '#111111'),
                'labelWeight' => (string) self::get_option_value(self::OPTION_STYLE_LABEL_WEIGHT, '600'),
                'focusRingColor' => (string) self::get_option_value(self::OPTION_STYLE_FOCUS_RING_COLOR, '#2563eb'),
                'astroFontSize' => (int) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_SIZE, 20),
                'astroFontColor' => (string) self::get_option_value(self::OPTION_STYLE_ASTRO_FONT_COLOR, '#111111'),
                'modalBgColor' => (string) self::get_option_value(self::OPTION_STYLE_MODAL_BG_COLOR, '#ffffff'),
                'modalTextColor' => (string) self::get_option_value(self::OPTION_STYLE_MODAL_TEXT_COLOR, '#111111'),
                'modalBorderColor' => (string) self::get_option_value(self::OPTION_STYLE_MODAL_BORDER_COLOR, '#111111'),
                'modalAccentColor' => (string) self::get_option_value(self::OPTION_STYLE_MODAL_ACCENT_COLOR, '#d97706'),
            ],
            'ephemerisStyle' => [
                'tableBgColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_BG_COLOR, '#ffffff'),
                'tableTextColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_TEXT_COLOR, '#111111'),
                'tableBorderColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_BORDER_COLOR, '#e5e7eb'),
                'headerBgColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_HEADER_BG_COLOR, '#f8fafc'),
                'headerTextColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_HEADER_TEXT_COLOR, '#111111'),
                'badgeBgColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_BADGE_BG_COLOR, '#f8fafc'),
                'badgeTextColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_BADGE_TEXT_COLOR, '#475569'),
                'todayBgColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_TODAY_BG_COLOR, '#f8fafc'),
                'todayAccentColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_TABLE_TODAY_ACCENT_COLOR, '#2563eb'),
                'retrogradeColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_RETROGRADE_COLOR, '#b91c1c'),
                'ingressColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_INGRESS_COLOR, '#1d4ed8'),
                'stationColor' => (string) self::get_option_value(self::OPTION_EPHEMERIS_STATION_COLOR, '#a16207'),
            ],
            'horoscope' => [
                'showTheme' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_THEME, true),
                'showSummary' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_SUMMARY, true),
                'showScores' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_SCORES, true),
                'showScoreFactors' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_SCORE_FACTORS, true),
                'showPersonalContext' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_PERSONAL_CONTEXT, true),
                'showLucky' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_LUCKY, true),
                'showGuidance' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_GUIDANCE, true),
                'showAstro' => (bool) self::get_option_value(self::OPTION_HOROSCOPE_SHOW_ASTRO, true),
            ],
            'horoscopeStyle' => [
                'cardBgColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CARD_BG_COLOR, '#ffffff'),
                'cardTextColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CARD_TEXT_COLOR, '#111111'),
                'cardBorderColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CARD_BORDER_COLOR, '#e5e7eb'),
                'themeBgColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_THEME_BG_COLOR, '#111827'),
                'themeTextColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_THEME_TEXT_COLOR, '#ffffff'),
                'chipBgColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CHIP_BG_COLOR, '#f9fafb'),
                'chipTextColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CHIP_TEXT_COLOR, '#111111'),
                'chipBorderColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_CHIP_BORDER_COLOR, '#e5e7eb'),
                'tileBgColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_TILE_BG_COLOR, '#f9fafb'),
                'tileTextColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_TILE_TEXT_COLOR, '#111111'),
                'tileBorderColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_TILE_BORDER_COLOR, '#e5e7eb'),
                'accentColor' => (string) self::get_option_value(self::OPTION_HOROSCOPE_ACCENT_COLOR, '#2563eb'),
            ],
            'botStyle' => [
                'shellBgColor' => (string) self::get_option_value(self::OPTION_BOT_SHELL_BG_COLOR, '#101014'),
                'headerBgColor' => (string) self::get_option_value(self::OPTION_BOT_HEADER_BG_COLOR, '#17231f'),
                'screenBgColor' => (string) self::get_option_value(self::OPTION_BOT_SCREEN_BG_COLOR, '#0f1f1b'),
                'accentColor' => (string) self::get_option_value(self::OPTION_BOT_ACCENT_COLOR, '#facc15'),
                'userBubbleBgColor' => (string) self::get_option_value(self::OPTION_BOT_USER_BUBBLE_BG_COLOR, '#d8f7c5'),
                'userBubbleTextColor' => (string) self::get_option_value(self::OPTION_BOT_USER_BUBBLE_TEXT_COLOR, '#142117'),
                'assistantBubbleBgColor' => (string) self::get_option_value(self::OPTION_BOT_ASSISTANT_BUBBLE_BG_COLOR, '#202c28'),
                'assistantBubbleTextColor' => (string) self::get_option_value(self::OPTION_BOT_ASSISTANT_BUBBLE_TEXT_COLOR, '#f1f5f9'),
            ],
            'typography' => [
                'h2' => (int) self::get_option_value(self::OPTION_TYPO_H2, 28),
                'h3' => (int) self::get_option_value(self::OPTION_TYPO_H3, 22),
                'h4' => (int) self::get_option_value(self::OPTION_TYPO_H4, 18),
                'body' => (int) self::get_option_value(self::OPTION_TYPO_BODY, 16),
                'table' => (int) self::get_option_value(self::OPTION_TYPO_TABLE, 14),
                'input' => (int) self::get_option_value(self::OPTION_TYPO_INPUT, 16),
                'button' => (int) self::get_option_value(self::OPTION_TYPO_BUTTON, 16),
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
        $node_type = self::western_node_type();
        if ($node_type === 'true') { $features[] = 'true_node'; }
        if ($node_type === 'mean') { $features[] = 'mean_node'; }
        if ((bool) self::get_option_value(self::OPTION_OBJ_PART_OF_FORTUNE, true)) { $features[] = 'part_of_fortune'; }

        return $features;
    }

    private static function add_config(string $handle): void
    {
        $config = wp_json_encode(self::frontend_config());
        wp_add_inline_script($handle, 'window.AstrologySuiteBlocks = Object.assign({}, window.AstrologySuiteBlocks || {}, ' . $config . '); window.OpenAstroBlocks = Object.assign({}, window.OpenAstroBlocks || {}, window.AstrologySuiteBlocks);', 'before');
    }

    private static function shortcode_names(): array
    {
        $shortcodes = ['astrology_suite', 'openastro', 'freeastro'];

        foreach (self::WIDGET_MODES as $mode) {
            $suffix = str_replace('-', '_', $mode);
            $shortcodes[] = 'astrology_suite_' . $suffix;
            $shortcodes[] = 'openastro_' . $suffix;
            $shortcodes[] = 'freeastro_' . $suffix;
        }

        return $shortcodes;
    }

    private static function current_request_has_shortcode(): bool
    {
        $posts = [];
        $post = get_post();
        if ($post instanceof \WP_Post) {
            $posts[] = $post;
        }

        global $wp_query;
        if ($wp_query instanceof \WP_Query && is_array($wp_query->posts)) {
            foreach ($wp_query->posts as $query_post) {
                if ($query_post instanceof \WP_Post) {
                    $posts[] = $query_post;
                }
            }
        }

        foreach ($posts as $candidate) {
            if (self::content_has_widget_shortcode((string) $candidate->post_content)) {
                return true;
            }
        }

        return false;
    }

    private static function content_has_widget_shortcode(string $content): bool
    {
        if ($content === '') {
            return false;
        }

        foreach (self::shortcode_names() as $shortcode) {
            if (has_shortcode($content, $shortcode)) {
                return true;
            }
        }

        return false;
    }

    private static function register_widget_assets(): void
    {
        if (!wp_script_is('openastro-suite-widgets-editor', 'registered')) {
            wp_register_script('openastro-suite-widgets-editor', OPENASTRO_SUITE_URL . 'assets/widgets-blocks.js', ['wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'], OPENASTRO_SUITE_VERSION, true);
        }

        if (!wp_script_is('openastro-suite-widgets-frontend', 'registered')) {
            wp_register_script('openastro-suite-widgets-frontend', OPENASTRO_SUITE_URL . 'assets/widgets-frontend.js', [], OPENASTRO_SUITE_VERSION, true);
            self::add_config('openastro-suite-widgets-frontend');
        }

        if (!wp_style_is('openastro-suite-widgets-style', 'registered')) {
            wp_register_style('openastro-suite-widgets-style', OPENASTRO_SUITE_URL . 'assets/widgets-style.css', [], OPENASTRO_SUITE_VERSION);
        }
    }

    private static function enqueue_widget_assets(): void
    {
        self::register_widget_assets();
        wp_enqueue_script('openastro-suite-widgets-frontend');
        wp_enqueue_style('openastro-suite-widgets-style');
    }

    private static function register_widget_blocks(): void
    {
        self::register_widget_assets();

        foreach (self::WIDGET_MODES as $slug) {
            foreach ([self::BLOCK_NAMESPACE, self::LEGACY_BLOCK_NAMESPACE] as $namespace) {
                $settings = [
                    'editor_script' => 'openastro-suite-widgets-editor',
                    'script' => 'openastro-suite-widgets-frontend',
                    'style' => 'openastro-suite-widgets-style',
                ];

                if (in_array($slug, ['vedic-chart', 'vedic-workbench'], true)) {
                    $settings['render_callback'] = static function (array $attributes = []) use ($slug): string {
                        return self::render_widget_container($slug, [
                            'vedic_preset' => $attributes['vedicPreset'] ?? '',
                            'vedic_layout' => $attributes['vedicLayout'] ?? '',
                            'vedic_divisions' => $attributes['vedicDivisions'] ?? [],
                            'vedic_modules' => $attributes['vedicModules'] ?? [],
                        ]);
                    };
                }

                register_block_type($namespace . '/' . $slug, $settings);
            }
        }
    }

    private static function shortcode_mode_from_tag(string $tag, string $fallback): string
    {
        $tag = sanitize_key($tag);
        foreach (['astrology_suite_', 'openastro_', 'freeastro_'] as $prefix) {
            if (str_starts_with($tag, $prefix)) {
                return self::normalize_widget_mode(substr($tag, strlen($prefix)));
            }
        }

        return self::normalize_widget_mode($fallback !== '' ? $fallback : 'chart-report');
    }

    private static function normalize_widget_mode(string $mode): string
    {
        $mode = sanitize_key(str_replace('_', '-', $mode));
        return in_array($mode, self::WIDGET_MODES, true) ? $mode : 'chart-report';
    }

    private static function normalize_interpretation_style(string $style): string
    {
        return sanitize_key($style) === 'poetic' ? 'poetic' : 'improved';
    }

    private static function shortcode_bool($value, bool $default): bool
    {
        $value = trim(strtolower((string) $value));
        if ($value === '') {
            return $default;
        }
        if (in_array($value, ['1', 'true', 'yes', 'on'], true)) {
            return true;
        }
        if (in_array($value, ['0', 'false', 'no', 'off'], true)) {
            return false;
        }
        return $default;
    }

    private static function sanitize_class_list(string $class): string
    {
        $classes = preg_split('/\s+/', trim($class));
        if (!is_array($classes)) {
            return '';
        }

        $clean = [];
        foreach ($classes as $item) {
            $sanitized = sanitize_html_class($item);
            if ($sanitized !== '') {
                $clean[] = $sanitized;
            }
        }

        return implode(' ', array_unique($clean));
    }

    private static function normalize_vedic_preset($value): string
    {
        $preset = sanitize_key((string) $value);
        return in_array($preset, ['guided', 'practitioner', 'custom'], true) ? $preset : '';
    }

    private static function normalize_vedic_layout($value): string
    {
        $layout = sanitize_key((string) $value);
        return in_array($layout, ['north_indian', 'south_indian', 'east_indian'], true) ? $layout : '';
    }

    private static function normalize_vedic_divisions($value, string $mode): array
    {
        $values = is_array($value) ? $value : preg_split('/[\s,]+/', (string) $value);
        $allowed = $mode === 'vedic-workbench'
            ? self::VEDIC_CALCULATION_DIVISIONS
            : self::VEDIC_VISUAL_DIVISIONS;
        $divisions = [];

        foreach (is_array($values) ? $values : [] as $division) {
            $division = (int) $division;
            if (in_array($division, $allowed, true) && !in_array($division, $divisions, true)) {
                $divisions[] = $division;
            }
        }

        return array_values(array_intersect($allowed, $divisions));
    }

    private static function normalize_vedic_modules($value): array
    {
        $values = is_array($value) ? $value : preg_split('/[\s,]+/', (string) $value);
        $allowed = ['yogas', 'panchang', 'shadbala', 'ashtakavarga', 'avastha'];
        $modules = [];

        foreach (is_array($values) ? $values : [] as $module) {
            $module = sanitize_key((string) $module);
            if (in_array($module, $allowed, true) && !in_array($module, $modules, true)) {
                $modules[] = $module;
            }
        }

        return array_values(array_intersect($allowed, $modules));
    }

    private static function render_widget_container(string $mode, array $attrs = []): string
    {
        $mode = self::normalize_widget_mode($mode);
        $include_interpretation = !isset($attrs['include_interpretation']) || (bool) $attrs['include_interpretation'];
        $interpretation_style = self::normalize_interpretation_style((string) ($attrs['interpretation_style'] ?? 'improved'));
        $language = self::normalize_frontend_language((string) ($attrs['language'] ?? 'auto'));
        $extra_class = self::sanitize_class_list((string) ($attrs['class'] ?? ''));
        $classes = trim('wp-block-astrology-suite-widget' . ($extra_class !== '' ? ' ' . $extra_class : ''));
        $vedic_preset = self::normalize_vedic_preset($attrs['vedic_preset'] ?? '');
        $vedic_layout = self::normalize_vedic_layout($attrs['vedic_layout'] ?? '');
        $vedic_divisions = self::normalize_vedic_divisions($attrs['vedic_divisions'] ?? [], $mode);
        $vedic_modules = self::normalize_vedic_modules($attrs['vedic_modules'] ?? []);
        $vedic_attributes = '';
        if ($vedic_preset !== '') {
            $vedic_attributes .= ' data-vedic-preset="' . esc_attr($vedic_preset) . '"';
        }
        if ($vedic_layout !== '') {
            $vedic_attributes .= ' data-vedic-layout="' . esc_attr($vedic_layout) . '"';
        }
        if ($vedic_divisions !== []) {
            $vedic_attributes .= ' data-vedic-divisions="' . esc_attr(implode(',', $vedic_divisions)) . '"';
        }
        if ($vedic_modules !== [] || $vedic_preset === 'custom') {
            $vedic_attributes .= ' data-vedic-modules="' . esc_attr(implode(',', $vedic_modules)) . '"';
        }

        return sprintf(
            '<div class="%1$s" data-astrology-suite-widget="1" data-mode="%2$s" data-include-interpretation="%3$s" data-interpretation-style="%4$s" data-language="%5$s"%6$s></div>',
            esc_attr($classes),
            esc_attr($mode),
            $include_interpretation ? '1' : '0',
            esc_attr($interpretation_style),
            esc_attr($language),
            $vedic_attributes
        );
    }
}
