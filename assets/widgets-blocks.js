(function (blocks, element, i18n, blockEditor, components) {
  var el = element.createElement;
  var Fragment = element.Fragment;
  var InspectorControls = blockEditor.InspectorControls;
  var PanelBody = components.PanelBody;
  var ToggleControl = components.ToggleControl;
  var SelectControl = components.SelectControl;
  var CheckboxControl = components.CheckboxControl;

  function blockSlug(def) {
    return def.name.split('/')[1] || '';
  }

  function isSimpleSign(def) {
    return blockSlug(def).indexOf('simple-sign-') === 0;
  }

  function isVedic(def) {
    return ['vedic-chart', 'vedic-workbench'].indexOf(blockSlug(def)) !== -1;
  }

  function isVedicWorkbench(def) {
    return blockSlug(def) === 'vedic-workbench';
  }

  function toggleArrayValue(values, value, checked) {
    var next = Array.isArray(values) ? values.slice() : [];
    var index = next.indexOf(value);
    if (checked && index === -1) next.push(value);
    if (!checked && index !== -1) next.splice(index, 1);
    return next.sort(function (a, b) { return Number(a) - Number(b); });
  }

  function vedicInspector(def, attrs, setAttributes) {
    var workbench = isVedicWorkbench(def);
    var custom = attrs.vedicPreset === 'custom';
    var divisions = workbench
      ? [[1, 'D1 Rashi'], [2, 'D2 Hora'], [3, 'D3 Drekkana'], [7, 'D7 Saptamsha'], [9, 'D9 Navamsha'], [10, 'D10 Dashamsha'], [12, 'D12 Dwadashamsha'], [30, 'D30 Trimshamsha'], [60, 'D60 Shashtiamsha']]
      : [[1, 'D1 Rashi'], [2, 'D2 Hora'], [3, 'D3 Drekkana'], [4, 'D4 Chaturthamsha'], [5, 'D5 Panchamsha'], [7, 'D7 Saptamsha'], [9, 'D9 Navamsha'], [10, 'D10 Dashamsha'], [12, 'D12 Dwadashamsha'], [16, 'D16 Shodashamsha'], [20, 'D20 Vimshamsha'], [24, 'D24 Chaturvimshamsha'], [27, 'D27 Saptavimshamsha'], [30, 'D30 Trimshamsha'], [40, 'D40 Khavedamsha'], [45, 'D45 Akshavedamsha'], [60, 'D60 Shashtiamsha']];
    var modules = [['yogas', 'Yogas and doshas'], ['panchang', 'Birth Panchang'], ['shadbala', 'Shadbala strength'], ['ashtakavarga', 'Ashtakavarga'], ['avastha', 'Baladi Avastha']];

    return el(PanelBody, {
      key: 'vedic-panel',
      title: i18n.__('Vedic Options', 'astrology-suite-freeastroapi'),
      initialOpen: true
    }, [
      el(SelectControl, {
        key: 'vedic-preset',
        label: i18n.__('Experience preset', 'astrology-suite-freeastroapi'),
        help: i18n.__('Guided is best for public visitors. Practitioner enables the complete reference workflow.', 'astrology-suite-freeastroapi'),
        value: attrs.vedicPreset || '',
        options: [
          { label: i18n.__('Use site default', 'astrology-suite-freeastroapi'), value: '' },
          { label: i18n.__('Guided', 'astrology-suite-freeastroapi'), value: 'guided' },
          { label: i18n.__('Practitioner', 'astrology-suite-freeastroapi'), value: 'practitioner' },
          { label: i18n.__('Custom', 'astrology-suite-freeastroapi'), value: 'custom' }
        ],
        onChange: function (value) { setAttributes({ vedicPreset: value }); }
      }),
      el(SelectControl, {
        key: 'vedic-layout',
        label: i18n.__('Kundli layout', 'astrology-suite-freeastroapi'),
        value: attrs.vedicLayout || '',
        options: [
          { label: i18n.__('Use site default', 'astrology-suite-freeastroapi'), value: '' },
          { label: i18n.__('North Indian', 'astrology-suite-freeastroapi'), value: 'north_indian' },
          { label: i18n.__('South Indian', 'astrology-suite-freeastroapi'), value: 'south_indian' },
          { label: i18n.__('East Indian', 'astrology-suite-freeastroapi'), value: 'east_indian' }
        ],
        onChange: function (value) { setAttributes({ vedicLayout: value }); }
      }),
      custom ? el('div', { key: 'custom-divisions' }, [
        el('p', { key: 'divisions-label' }, el('strong', null, i18n.__('Divisional charts', 'astrology-suite-freeastroapi'))),
        divisions.map(function (division) {
          return el(CheckboxControl, {
            key: 'division-' + division[0],
            label: division[1],
            checked: (attrs.vedicDivisions || []).indexOf(division[0]) !== -1,
            onChange: function (checked) {
              setAttributes({ vedicDivisions: toggleArrayValue(attrs.vedicDivisions, division[0], checked) });
            }
          });
        })
      ]) : null,
      custom && workbench ? el('div', { key: 'custom-modules' }, [
        el('p', { key: 'modules-label' }, el('strong', null, i18n.__('Result modules', 'astrology-suite-freeastroapi'))),
        modules.map(function (module) {
          return el(CheckboxControl, {
            key: 'module-' + module[0],
            label: module[1],
            checked: (attrs.vedicModules || []).indexOf(module[0]) !== -1,
            onChange: function (checked) {
              setAttributes({ vedicModules: toggleArrayValue(attrs.vedicModules, module[0], checked) });
            }
          });
        })
      ]) : null
    ]);
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
        },
        vedicPreset: {
          type: 'string',
          default: ''
        },
        vedicLayout: {
          type: 'string',
          default: ''
        },
        vedicDivisions: {
          type: 'array',
          default: []
        },
        vedicModules: {
          type: 'array',
          default: []
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
          isVedic(def)
            ? el(InspectorControls, { key: 'vedic-inspector' }, [vedicInspector(def, attrs, setAttributes)])
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
        var divisions = Array.isArray(attrs.vedicDivisions) ? attrs.vedicDivisions.join(',') : '';
        var modules = Array.isArray(attrs.vedicModules) ? attrs.vedicModules.join(',') : '';

        return el('div', {
          className: 'wp-block-astrology-suite-widget',
          'data-astrology-suite-widget': '1',
          'data-mode': mode,
          'data-include-interpretation': includeInterpretation,
          'data-interpretation-style': interpretationStyle,
          'data-vedic-preset': attrs.vedicPreset || undefined,
          'data-vedic-layout': attrs.vedicLayout || undefined,
          'data-vedic-divisions': divisions || undefined,
          'data-vedic-modules': modules || undefined
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
