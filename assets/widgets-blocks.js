(function (blocks, element, i18n, blockEditor, components) {
  var el = element.createElement;
  var Fragment = element.Fragment;
  var InspectorControls = blockEditor.InspectorControls;
  var PanelBody = components.PanelBody;
  var ToggleControl = components.ToggleControl;
  var SelectControl = components.SelectControl;

  function blockSlug(def) {
    return def.name.split('/')[1] || '';
  }

  function isSimpleSign(def) {
    return blockSlug(def).indexOf('simple-sign-') === 0;
  }

  function registerWidgetBlock(def, isLegacy) {
    blocks.registerBlockType(def.name, {
      apiVersion: 3,
      title: isLegacy ? def.title + ' (Legacy)' : def.title,
      icon: def.icon,
      category: 'widgets',
      supports: isLegacy ? { inserter: false } : {},
      attributes: {
        includeInterpretation: {
          type: 'boolean',
          default: true
        },
        interpretationStyle: {
          type: 'string',
          default: 'improved'
        }
      },
      edit: function (props) {
        var attrs = props.attributes;
        var setAttributes = props.setAttributes;

        return el(Fragment, null, [
          isSimpleSign(def)
            ? el(InspectorControls, { key: 'inspector' }, [
                el(PanelBody, {
                  key: 'panel',
                  title: i18n.__('Simple Sign Options', 'astrology-suite-freeastroapi'),
                  initialOpen: true
                }, [
                  el(ToggleControl, {
                    key: 'toggle',
                    label: i18n.__('Include interpretation text', 'astrology-suite-freeastroapi'),
                    checked: attrs.includeInterpretation !== false,
                    onChange: function (value) {
                      setAttributes({ includeInterpretation: !!value });
                    }
                  }),
                  attrs.includeInterpretation !== false
                    ? el(SelectControl, {
                        key: 'style',
                        label: i18n.__('Interpretation style', 'astrology-suite-freeastroapi'),
                        value: attrs.interpretationStyle || 'improved',
                        options: [
                          { label: i18n.__('Improved', 'astrology-suite-freeastroapi'), value: 'improved' },
                          { label: i18n.__('Poetic', 'astrology-suite-freeastroapi'), value: 'poetic' }
                        ],
                        onChange: function (value) {
                          setAttributes({ interpretationStyle: value });
                        }
                      })
                    : null
                ])
              ])
            : null,
          el('div', { key: 'preview', className: 'astrology-suite-editor-placeholder openastro-editor-placeholder' }, [
            el('strong', { key: 't' }, def.title),
            el('p', { key: 'd' }, def.description),
            isLegacy
              ? el('p', { key: 'legacy' }, i18n.__('Legacy openastro block. Use the migrator to convert saved content to astrology-suite blocks.', 'astrology-suite-freeastroapi'))
              : el('p', { key: 'n' }, i18n.__('Rendered on front end with built-in input form.', 'astrology-suite-freeastroapi'))
          ])
        ]);
      },
      save: function (props) {
        var mode = blockSlug(def);
        var attrs = props.attributes || {};
        var includeInterpretation = attrs.includeInterpretation === false ? '0' : '1';
        var interpretationStyle = attrs.interpretationStyle === 'poetic' ? 'poetic' : 'improved';

        return el('div', {
          className: 'wp-block-astrology-suite-widget',
          'data-astrology-suite-widget': '1',
          'data-mode': mode,
          'data-include-interpretation': includeInterpretation,
          'data-interpretation-style': interpretationStyle
        });
      }
    });
  }

  var defs = [
    {
      name: 'astrology-suite/chart-only',
      title: i18n.__('Astrology Suite Chart Only', 'astrology-suite-freeastroapi'),
      icon: 'chart-pie',
      description: i18n.__('Includes input form + natal wheel chart (no legend).', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/report-only',
      title: i18n.__('Astrology Suite Report Only', 'astrology-suite-freeastroapi'),
      icon: 'media-document',
      description: i18n.__('Includes input form + full interpretation report.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/chart-report',
      title: i18n.__('Astrology Suite Chart + Report', 'astrology-suite-freeastroapi'),
      icon: 'chart-line',
      description: i18n.__('Includes input form + chart + full report.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/simple-sign-sun',
      title: i18n.__('Simple Sign: Sun', 'astrology-suite-freeastroapi'),
      icon: 'star-filled',
      description: i18n.__('Includes input form + Sun sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/simple-sign-moon',
      title: i18n.__('Simple Sign: Moon', 'astrology-suite-freeastroapi'),
      icon: 'star-half',
      description: i18n.__('Includes input form + Moon sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/simple-sign-rising',
      title: i18n.__('Simple Sign: Rising', 'astrology-suite-freeastroapi'),
      icon: 'arrow-up-alt',
      description: i18n.__('Includes input form + Rising sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/simple-sign-midheaven',
      title: i18n.__('Simple Sign: Midheaven', 'astrology-suite-freeastroapi'),
      icon: 'arrow-up',
      description: i18n.__('Includes input form + Midheaven sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/ephemeris',
      title: i18n.__('Astrology Suite Ephemeris', 'astrology-suite-freeastroapi'),
      icon: 'calendar-alt',
      description: i18n.__('Includes monthly ephemeris table with selectable bodies and optional local points.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/daily-horoscope',
      title: i18n.__('Astrology Suite Daily Horoscope', 'astrology-suite-freeastroapi'),
      icon: 'welcome-view-site',
      description: i18n.__('Includes sign, date, and timezone controls with a daily horoscope result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/personalized-horoscope',
      title: i18n.__('Astrology Suite Personalized Horoscope', 'astrology-suite-freeastroapi'),
      icon: 'id',
      description: i18n.__('Includes birth details, city autocomplete, target date, and a personalized daily horoscope result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/vedic-chart',
      title: i18n.__('Vedic Visual Chart', 'astrology-suite-freeastroapi'),
      icon: 'chart-pie',
      description: i18n.__('Renders D1, D9, and other divisional charts in North, South, or East Indian Kundli layouts.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/vedic-workbench',
      title: i18n.__('Vedic Astrology Workbench', 'astrology-suite-freeastroapi'),
      icon: 'analytics',
      description: i18n.__('A Vedic chart and practitioner reference with grahas, vargas, dashas, yogas, Panchang, Shadbala, and Ashtakavarga.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'astrology-suite/astrology-bot',
      title: i18n.__('Astrology Suite Bot', 'astrology-suite-freeastroapi'),
      icon: 'format-chat',
      description: i18n.__('Embeds the FreeAstroAPI Western/Vedic astrology chat component with birth profile and style controls.', 'astrology-suite-freeastroapi')
    }
  ];

  defs.forEach(function (def) {
    registerWidgetBlock(def, false);
    registerWidgetBlock(Object.assign({}, def, { name: def.name.replace('astrology-suite/', 'openastro/') }), true);
  });
})(window.wp.blocks, window.wp.element, window.wp.i18n, window.wp.blockEditor, window.wp.components);
