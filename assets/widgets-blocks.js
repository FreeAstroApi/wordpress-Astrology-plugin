(function (blocks, element, i18n, blockEditor, components) {
  var el = element.createElement;
  var Fragment = element.Fragment;
  var InspectorControls = blockEditor.InspectorControls;
  var PanelBody = components.PanelBody;
  var ToggleControl = components.ToggleControl;
  var SelectControl = components.SelectControl;

  function isSimpleSign(def) {
    return def.name.indexOf('openastro/simple-sign-') === 0;
  }

  var defs = [
    {
      name: 'openastro/chart-only',
      title: i18n.__('Astrology Suite Chart Only', 'astrology-suite-freeastroapi'),
      icon: 'chart-pie',
      description: i18n.__('Includes input form + natal wheel chart (no legend).', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/report-only',
      title: i18n.__('Astrology Suite Report Only', 'astrology-suite-freeastroapi'),
      icon: 'media-document',
      description: i18n.__('Includes input form + full interpretation report.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/chart-report',
      title: i18n.__('Astrology Suite Chart + Report', 'astrology-suite-freeastroapi'),
      icon: 'chart-line',
      description: i18n.__('Includes input form + chart + full report.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/simple-sign-sun',
      title: i18n.__('Simple Sign: Sun', 'astrology-suite-freeastroapi'),
      icon: 'star-filled',
      description: i18n.__('Includes input form + Sun sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/simple-sign-moon',
      title: i18n.__('Simple Sign: Moon', 'astrology-suite-freeastroapi'),
      icon: 'star-half',
      description: i18n.__('Includes input form + Moon sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/simple-sign-rising',
      title: i18n.__('Simple Sign: Rising', 'astrology-suite-freeastroapi'),
      icon: 'arrow-up-alt',
      description: i18n.__('Includes input form + Rising sign result.', 'astrology-suite-freeastroapi')
    },
    {
      name: 'openastro/simple-sign-midheaven',
      title: i18n.__('Simple Sign: Midheaven', 'astrology-suite-freeastroapi'),
      icon: 'arrow-up',
      description: i18n.__('Includes input form + Midheaven sign result.', 'astrology-suite-freeastroapi')
    }
  ];

  defs.forEach(function (def) {
    blocks.registerBlockType(def.name, {
      apiVersion: 3,
      title: def.title,
      icon: def.icon,
      category: 'widgets',
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
          el('div', { key: 'preview', className: 'openastro-editor-placeholder' }, [
            el('strong', { key: 't' }, def.title),
            el('p', { key: 'd' }, def.description),
            el('p', { key: 'n' }, i18n.__('Rendered on front end with built-in input form.', 'astrology-suite-freeastroapi'))
          ])
        ]);
      },
      save: function (props) {
        var mode = def.name.replace('openastro/', '');
        var attrs = props.attributes || {};
        var includeInterpretation = attrs.includeInterpretation === false ? '0' : '1';
        var interpretationStyle = attrs.interpretationStyle === 'poetic' ? 'poetic' : 'improved';

        return el('div', {
          className: 'wp-block-openastro-widget',
          'data-openastro-widget': '1',
          'data-mode': mode,
          'data-include-interpretation': includeInterpretation,
          'data-interpretation-style': interpretationStyle
        });
      }
    });
  });
})(window.wp.blocks, window.wp.element, window.wp.i18n, window.wp.blockEditor, window.wp.components);
