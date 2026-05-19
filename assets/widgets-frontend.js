(function () {
  var PLANET_OVERVIEW = {
    sun: 'The Sun reflects core identity, vitality, and the drive to become fully oneself.',
    moon: 'The Moon describes emotional needs, instinctive reactions, and inner security patterns.',
    mercury: 'Mercury shows how the mind perceives, learns, and communicates.',
    venus: 'Venus indicates relationship style, values, attraction, and harmony.',
    mars: 'Mars represents action, assertion, desire, and the way energy is directed.',
    jupiter: 'Jupiter expands perspective, beliefs, and pathways to growth and meaning.',
    saturn: 'Saturn defines responsibility, structure, discipline, and long-term mastery.',
    uranus: 'Uranus marks originality, disruption, awakening, and the need for freedom.',
    neptune: 'Neptune symbolizes imagination, sensitivity, ideals, and subtle inner perception.',
    pluto: 'Pluto points to deep transformation, power dynamics, and regenerative change.',
    north_node: 'The North Node highlights life direction, growth edges, and developmental themes.',
    lilith: 'Lilith reveals instinctive shadow material, autonomy themes, and denied desires.',
    chiron: 'Chiron marks core wounds and the path toward integration and healing wisdom.'
  };

  var BODY_GLYPHS = {
    sun: 's',
    moon: 'a',
    mercury: 'f',
    venus: 'g',
    mars: 'h',
    jupiter: 'j',
    saturn: 's',
    uranus: 'F',
    neptune: 'G',
    pluto: 'J',
    north_node: 'k',
    true_node: 'k',
    mean_node: 'k',
    lilith: 'L',
    chiron: 'D'
  };

  var SIGN_GLYPHS = {
    aries: 'x',
    taurus: 'c',
    gemini: 'v',
    cancer: 'b',
    leo: 'n',
    virgo: 'm',
    libra: 'X',
    scorpio: 'C',
    sagittarius: 'V',
    capricorn: 'B',
    aquarius: 'N',
    pisces: 'M',
    ari: 'x',
    tau: 'c',
    gem: 'v',
    can: 'b',
    vir: 'm',
    lib: 'X',
    sco: 'C',
    sag: 'V',
    cap: 'B',
    aqu: 'N',
    pis: 'M'
  };
  var HISTORY_KEY = 'openastro_history_v1';
  var HISTORY_LIMIT = 20;

  function getCfg() {
    var cfg = window.OpenAstroBlocks || { root: '/wp-json/openastro/v1/', nonce: '', permissions: {}, report: {}, style: {}, typography: {} };
    cfg.permissions = cfg.permissions || {};
    cfg.report = cfg.report || {};
    cfg.style = cfg.style || {};
    cfg.typography = cfg.typography || {};
    cfg.permissions.canCalculate = !!cfg.permissions.canCalculate;
    cfg.permissions.calculationMessage = cfg.permissions.calculationMessage || 'This astrology form is available only to logged-in users who can edit posts on this site.';
    cfg.report.detailLevel = cfg.report.detailLevel === 'concise' ? 'concise' : 'full';
    cfg.report.maxPlanets = Number(cfg.report.maxPlanets || 13);
    cfg.report.maxAspects = Number(cfg.report.maxAspects || 40);
    cfg.report.showTables = cfg.report.showTables !== false;
    cfg.report.showAngles = cfg.report.showAngles !== false;
    cfg.report.showPrintButton = cfg.report.showPrintButton !== false;
    cfg.style.showBorder = cfg.style.showBorder !== false;
    cfg.style.backgroundColor = cfg.style.backgroundColor || '#ffffff';
    cfg.style.textColor = cfg.style.textColor || '#111111';
    cfg.style.inputBorderRadius = Number(cfg.style.inputBorderRadius || 0);
    cfg.style.inputBorderColor = cfg.style.inputBorderColor || '#000000';
    cfg.style.inputBgColor = cfg.style.inputBgColor || '#ffffff';
    cfg.style.inputTextColor = cfg.style.inputTextColor || '#111111';
    cfg.style.inputPlaceholderColor = cfg.style.inputPlaceholderColor || '#777777';
    cfg.style.buttonBorderRadius = Number(cfg.style.buttonBorderRadius || 0);
    cfg.style.buttonBgColor = cfg.style.buttonBgColor || '#ffffff';
    cfg.style.buttonTextColor = cfg.style.buttonTextColor || '#000000';
    cfg.style.buttonBorderColor = cfg.style.buttonBorderColor || '#000000';
    cfg.style.labelColor = cfg.style.labelColor || '#111111';
    cfg.style.labelWeight = cfg.style.labelWeight || 'normal';
    cfg.style.focusRingColor = cfg.style.focusRingColor || '#222222';
    cfg.style.astroFontSize = Number(cfg.style.astroFontSize || 20);
    cfg.style.astroFontColor = cfg.style.astroFontColor || '#111111';
    cfg.typography.h2 = Number(cfg.typography.h2 || 28);
    cfg.typography.h3 = Number(cfg.typography.h3 || 22);
    cfg.typography.h4 = Number(cfg.typography.h4 || 18);
    cfg.typography.body = Number(cfg.typography.body || 16);
    cfg.typography.table = Number(cfg.typography.table || 14);
    cfg.typography.input = Number(cfg.typography.input || 18);
    cfg.typography.button = Number(cfg.typography.button || 20);
    cfg.typography.meta = Number(cfg.typography.meta || 13);
    return cfg;
  }

  function chartSectionHtml(svg, reportCfg) {
    var printButton = reportCfg.showPrintButton
      ? '<button type="button" class="openastro-print-btn" data-openastro-print-chart="1">Print</button>'
      : '';
    return '<section class="openastro-card openastro-chart-card"><h3>Natal Wheel</h3>' + printButton + '<div class="openastro-chart">' + (svg || '<p>Chart unavailable.</p>') + '</div></section>';
  }

  function isSimpleSignMode(mode) {
    return mode === 'simple-sign-sun' ||
      mode === 'simple-sign-moon' ||
      mode === 'simple-sign-rising' ||
      mode === 'simple-sign-midheaven';
  }

  function pointForSimpleMode(mode) {
    if (mode === 'simple-sign-sun') return 'sun';
    if (mode === 'simple-sign-moon') return 'moon';
    if (mode === 'simple-sign-rising') return 'rising';
    return 'mc';
  }

  function prettyPoint(point) {
    var p = String(point || '').toLowerCase();
    if (p === 'sun') return 'Sun';
    if (p === 'moon') return 'Moon';
    if (p === 'rising') return 'Rising';
    if (p === 'mc' || p === 'midheaven') return 'Midheaven';
    return point || 'Point';
  }

  function simpleSignHtml(result, includeInterpretation) {
    var point = prettyPoint(result.point || '');
    var sign = result.sign || result.sign_id || 'Unknown';
    var signGlyph = glyphForSign(sign);
    var degree = typeof result.degree === 'number' ? result.degree.toFixed(2) + '°' : '';
    var interp = result.interpretation && result.interpretation.body ? result.interpretation.body : '';

    var html = '<section class="openastro-card openastro-simple-sign-card">';
    html += '<h3>' + esc(point) + ' Sign</h3>';
    html += '<p class="openastro-simple-sign-value">' + glyphSpan(signGlyph) + esc(sign) + (degree ? ' · ' + esc(degree) : '') + '</p>';

    if (includeInterpretation) {
      html += '<div class="openastro-simple-sign-interpretation">';
      html += '<h4>Interpretation</h4>';
      html += '<p>' + esc(interp || 'Interpretation was not returned for this point.') + '</p>';
      html += '</div>';
    }

    html += '</section>';
    return html;
  }

  function printChartCard(card) {
    var chartEl = card ? card.querySelector('.openastro-chart') : null;
    var svgEl = chartEl ? chartEl.querySelector('svg') : null;

    if (!svgEl) {
      window.print();
      return;
    }

    var printWindow = window.open('', '_blank', 'width=900,height=900');
    if (!printWindow) {
      window.print();
      return;
    }

    var svgMarkup = svgEl.outerHTML;
    var html = '<!doctype html><html><head><meta charset="utf-8"><title>Natal Wheel</title>' +
      '<style>body{margin:0;padding:24px;font-family:system-ui,sans-serif;}svg{width:100%;height:auto;max-width:760px;display:block;margin:0 auto;}</style>' +
      '</head><body>' + svgMarkup + '</body></html>';

    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
  }

  function create(tag, attrs, text) {
    var node = document.createElement(tag);
    if (attrs) {
      Object.keys(attrs).forEach(function (k) {
        if (k === 'checked') {
          node.checked = !!attrs[k];
          return;
        }
        node.setAttribute(k, attrs[k]);
      });
    }
    if (text) {
      node.textContent = text;
    }
    return node;
  }

  function esc(v) {
    return String(v)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function glyphForBody(id) {
    return BODY_GLYPHS[String(id || '').toLowerCase()] || '';
  }

  function glyphForSign(sign) {
    return SIGN_GLYPHS[String(sign || '').toLowerCase()] || '';
  }

  function glyphSpan(char) {
    if (!char) return '';
    return '<span class="openastro-astro-glyph">' + esc(char) + '</span>';
  }

  function api(path, options) {
    var cfg = getCfg();
    var headers = options.headers || {};
    if (cfg.nonce) {
      headers['X-WP-Nonce'] = cfg.nonce;
    }

    return fetch(cfg.root.replace(/\/$/, '') + path, Object.assign({}, options, { headers: headers }))
      .then(function (r) {
        return r.json().then(function (body) {
          return { ok: r.ok, body: body };
        });
      });
  }

  function safeJsonParse(raw) {
    try {
      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  }

  function getHistory() {
    try {
      var raw = window.localStorage.getItem(HISTORY_KEY);
      if (!raw) return [];
      var parsed = safeJsonParse(raw);
      if (!Array.isArray(parsed)) {
        window.localStorage.removeItem(HISTORY_KEY);
        return [];
      }
      return parsed;
    } catch (e) {
      return [];
    }
  }

  function saveHistory(history) {
    try {
      window.localStorage.setItem(HISTORY_KEY, JSON.stringify(history.slice(0, HISTORY_LIMIT)));
    } catch (e) {
      // Ignore storage issues to keep widget functional.
    }
  }

  function pad2(v) {
    var n = Number(v || 0);
    return n < 10 ? '0' + n : String(n);
  }

  function toDateValue(entry) {
    return [entry.year, pad2(entry.month), pad2(entry.day)].join('-');
  }

  function toTimeValue(entry) {
    return pad2(entry.hour) + ':' + pad2(entry.minute);
  }

  function normalizeNum(v, fixed) {
    var num = Number(v || 0);
    return num.toFixed(fixed || 4);
  }

  function historyIdFromEntry(entry) {
    return [
      String(entry.year || ''),
      String(entry.month || ''),
      String(entry.day || ''),
      String(entry.hour || ''),
      String(entry.minute || ''),
      normalizeNum(entry.lat, 4),
      normalizeNum(entry.lng, 4)
    ].join('|');
  }

  function makeHistoryEntry(mode, payload, selectedCity, includeMinor) {
    var country = selectedCity.country || selectedCity.country_name || selectedCity.country_code || '';
    var entry = {
      saved_at: new Date().toISOString(),
      name: payload.name || '',
      year: Number(payload.year || 0),
      month: Number(payload.month || 0),
      day: Number(payload.day || 0),
      hour: Number(payload.hour || 0),
      minute: Number(payload.minute || 0),
      city_name: selectedCity.name || payload.city || '',
      country: country,
      timezone: selectedCity.timezone || payload.tz_str || '',
      lat: Number(selectedCity.lat || payload.lat || 0),
      lng: Number(selectedCity.lng || payload.lng || 0),
      include_minor_aspects: !!includeMinor,
      last_mode: mode
    };
    entry.id = historyIdFromEntry(entry);
    return entry;
  }

  function upsertHistory(entry) {
    var history = getHistory();
    var deduped = history.filter(function (h) {
      return historyIdFromEntry(h) !== entry.id;
    });
    deduped.unshift(entry);
    if (deduped.length > HISTORY_LIMIT) {
      deduped = deduped.slice(0, HISTORY_LIMIT);
    }
    saveHistory(deduped);
    return deduped;
  }

  function historyOptionLabel(entry) {
    var datePart = [pad2(entry.day), pad2(entry.month), String(entry.year)].join('/');
    var timePart = [pad2(entry.hour), pad2(entry.minute)].join(':');
    var place = entry.city_name || 'Unknown city';
    var country = entry.country ? ' (' + entry.country + ')' : '';
    var person = String(entry.name || '').trim();
    var personPrefix = person ? person + ' - ' : '';
    return personPrefix + datePart + ' ' + timePart + ' - ' + place + country;
  }

  function textFromEntry(entry) {
    if (!entry) return '';
    if (typeof entry === 'string') return entry.trim();
    if (typeof entry !== 'object') return '';
    var candidates = ['body', 'text', 'content', 'description', 'interpretation', 'summary'];
    for (var i = 0; i < candidates.length; i += 1) {
      var v = entry[candidates[i]];
      if (typeof v === 'string' && v.trim()) {
        return v.trim();
      }
    }
    return '';
  }

  function findAngleAtom(atoms, token) {
    var t = token.toLowerCase();
    for (var i = 0; i < atoms.length; i += 1) {
      var atom = atoms[i];
      var title = String(atom.title || '').toLowerCase();
      var key = String(atom.key || '').toLowerCase();
      if (title.indexOf(t) >= 0 || key.indexOf(t) >= 0) {
        return atom;
      }
    }
    return null;
  }

  function collectInterpretationAtoms(interpretation) {
    if (!interpretation || typeof interpretation !== 'object' || Array.isArray(interpretation)) {
      return [];
    }

    var sections = interpretation.sections;
    if (!sections || typeof sections !== 'object' || Array.isArray(sections)) {
      return [];
    }

    var out = [];
    Object.keys(sections).forEach(function (cat) {
      var entries = sections[cat];
      if (!Array.isArray(entries)) {
        return;
      }
      entries.forEach(function (entry) {
        var title = '';
        var body = '';
        var key = '';
        if (entry && typeof entry === 'object') {
          title = typeof entry.title === 'string' ? entry.title.trim() : '';
          body = textFromEntry(entry);
          key = typeof entry.key === 'string' ? entry.key : '';
        } else if (typeof entry === 'string') {
          body = entry.trim();
        }
        if (!title) {
          title = cat.charAt(0).toUpperCase() + cat.slice(1);
        }
        if (!title || !body) {
          return;
        }
        out.push({
          category: cat,
          title: title,
          body: body,
          key: key
        });
      });
    });

    return out;
  }

  function cropText(text, detail) {
    if (detail !== 'concise') {
      return text;
    }

    var s = String(text || '');
    if (s.length <= 240) {
      return s;
    }

    return s.slice(0, 240).trim() + '...';
  }

  function findPlanetAtom(atoms, planet) {
    var id = String(planet.id || '').toLowerCase();
    var name = String(planet.name || '').toLowerCase();

    for (var i = 0; i < atoms.length; i += 1) {
      var atom = atoms[i];
      var title = atom.title.toLowerCase();
      var key = atom.key.toLowerCase();
      if (key.indexOf('planet.' + id + '.') >= 0 || title.indexOf(name + ' in ') === 0) {
        return atom;
      }
    }

    return null;
  }

  function reportHtml(response, svg, mode, reportCfg) {
    var planets = Array.isArray(response.planets) ? response.planets : [];
    var aspects = Array.isArray(response.aspects) ? response.aspects.slice() : [];
    var atoms = collectInterpretationAtoms(response.interpretation);

    aspects.sort(function (a, b) {
      return Number(a.orb || 0) - Number(b.orb || 0);
    });

    var html = '<h2 class="openastro-report-title">Natal Report</h2>';

    if (mode === 'chart-report' && svg) {
      html += chartSectionHtml(svg, reportCfg);
    }

    if (reportCfg.showAngles) {
      var ascAtom = findAngleAtom(atoms, 'ascendant') || findAngleAtom(atoms, 'asc');
      var mcAtom = findAngleAtom(atoms, 'midheaven') || findAngleAtom(atoms, 'mc');
      var asc = response.angles_details && response.angles_details.asc ? response.angles_details.asc : null;
      var mc = response.angles_details && response.angles_details.mc ? response.angles_details.mc : null;

      html += '<section class="openastro-card"><h3>1st House / ASC</h3>';
      if (asc) {
        var ascSignGlyph = glyphForSign(asc.sign);
        html += '<p><strong>Sign:</strong> ' + esc(asc.sign || '-') + (ascSignGlyph ? ' ' + glyphSpan(ascSignGlyph) : '') + ' <strong>House:</strong> ' + esc(asc.house || '-') + '</p>';
      }
      html += '<p>' + esc(cropText(ascAtom ? ascAtom.body : 'Ascendant interpretation was not returned for this chart.', reportCfg.detailLevel)) + '</p>';
      html += '</section>';

      html += '<section class="openastro-card"><h3>10th House Cusp / MC</h3>';
      if (mc) {
        var mcSignGlyph = glyphForSign(mc.sign);
        html += '<p><strong>Sign:</strong> ' + esc(mc.sign || '-') + (mcSignGlyph ? ' ' + glyphSpan(mcSignGlyph) : '') + ' <strong>House:</strong> ' + esc(mc.house || '-') + '</p>';
      }
      html += '<p>' + esc(cropText(mcAtom ? mcAtom.body : 'Midheaven interpretation was not returned for this chart.', reportCfg.detailLevel)) + '</p>';
      html += '</section>';
    }

    html += '<section class="openastro-card"><h3>Planet Interpretations</h3>';
    planets.slice(0, reportCfg.maxPlanets).forEach(function (planet) {
      var atom = findPlanetAtom(atoms, planet);
      var defaultBody = PLANET_OVERVIEW[String(planet.id || '').toLowerCase()] || 'Interpretation not available for this planet.';
      var body = atom ? atom.body : defaultBody;
      var bodyGlyph = glyphForBody(planet.id);
      var signGlyph = glyphForSign(planet.sign);
      var planetName = planet.name || planet.id || '-';
      var signName = planet.sign || '-';
      var houseLabel = planet.house || '-';
      html += '<article class="openastro-planet-item">';
      html += '<h4>' + esc(planetName) + (bodyGlyph ? ' ' + glyphSpan(bodyGlyph) : '') + esc(' in ' + signName) + (signGlyph ? ' ' + glyphSpan(signGlyph) : '') + esc(' (House ' + houseLabel + ')') + '</h4>';
      html += '<p>' + esc(cropText(body, reportCfg.detailLevel)) + '</p>';
      html += '</article>';
    });
    html += '</section>';

    html += '<section class="openastro-card"><h3>All Aspect Dynamics</h3>';
    if (reportCfg.showTables) {
      html += '<table><thead><tr><th>P1</th><th>Aspect</th><th>P2</th><th>Orb</th><th>Major</th></tr></thead><tbody>';
      aspects.slice(0, reportCfg.maxAspects).forEach(function (a) {
        html += '<tr>' +
          '<td>' + esc(a.p1 || '-') + '</td>' +
          '<td>' + esc(a.type || '-') + '</td>' +
          '<td>' + esc(a.p2 || '-') + '</td>' +
          '<td>' + esc(typeof a.orb === 'number' ? a.orb.toFixed(2) : a.orb || '-') + '</td>' +
          '<td>' + esc(a.is_major ? 'Yes' : 'No') + '</td>' +
          '</tr>';
      });
      html += '</tbody></table>';
    } else {
      html += '<p>' + esc(aspects.length) + ' aspects detected.</p>';
    }
    html += '</section>';

    return html;
  }

  function mount(container) {
    var mode = container.getAttribute('data-mode') || 'chart-report';
    var cfg = getCfg();
    var includeInterpretation = container.getAttribute('data-include-interpretation') !== '0';
    var interpretationStyle = container.getAttribute('data-interpretation-style') === 'poetic' ? 'poetic' : 'improved';
    var simpleMode = isSimpleSignMode(mode);

    container.style.setProperty('--openastro-bg', cfg.style.backgroundColor);
    container.style.setProperty('--openastro-text', cfg.style.textColor);
    container.style.setProperty('--openastro-input-radius', cfg.style.inputBorderRadius + 'px');
    container.style.setProperty('--openastro-input-border-color', cfg.style.inputBorderColor);
    container.style.setProperty('--openastro-input-bg', cfg.style.inputBgColor);
    container.style.setProperty('--openastro-input-text', cfg.style.inputTextColor);
    container.style.setProperty('--openastro-input-placeholder', cfg.style.inputPlaceholderColor);
    container.style.setProperty('--openastro-button-radius', cfg.style.buttonBorderRadius + 'px');
    container.style.setProperty('--openastro-button-bg', cfg.style.buttonBgColor);
    container.style.setProperty('--openastro-button-text', cfg.style.buttonTextColor);
    container.style.setProperty('--openastro-button-border', cfg.style.buttonBorderColor);
    container.style.setProperty('--openastro-label-color', cfg.style.labelColor);
    container.style.setProperty('--openastro-label-weight', cfg.style.labelWeight);
    container.style.setProperty('--openastro-focus-ring', cfg.style.focusRingColor);
    container.style.setProperty('--openastro-astro-font-size', cfg.style.astroFontSize + 'px');
    container.style.setProperty('--openastro-astro-font-color', cfg.style.astroFontColor);
    container.style.setProperty('--openastro-font-h2', cfg.typography.h2 + 'px');
    container.style.setProperty('--openastro-font-h3', cfg.typography.h3 + 'px');
    container.style.setProperty('--openastro-font-h4', cfg.typography.h4 + 'px');
    container.style.setProperty('--openastro-font-body', cfg.typography.body + 'px');
    container.style.setProperty('--openastro-font-table', cfg.typography.table + 'px');
    container.style.setProperty('--openastro-font-input', cfg.typography.input + 'px');
    container.style.setProperty('--openastro-font-button', cfg.typography.button + 'px');
    container.style.setProperty('--openastro-font-meta', cfg.typography.meta + 'px');
    if (cfg.style.showBorder === false) {
      container.classList.add('openastro-no-border');
    } else {
      container.classList.remove('openastro-no-border');
    }

    if (!cfg.permissions.canCalculate) {
      var accessNote = create('section', { class: 'openastro-card openastro-access-note' });
      accessNote.appendChild(create('h3', null, 'Calculation Access Required'));
      accessNote.appendChild(create('p', null, cfg.permissions.calculationMessage));
      container.appendChild(accessNote);
      return;
    }

    var form = create('form', { class: 'openastro-form' });
    var formHeader = create('div', { class: 'openastro-form-header' });
    var historyWrap = create('div', { class: 'openastro-load-recent-wrap' });
    var historyPanel = create('div', { class: 'openastro-load-recent-panel' });
    var historyId = 'openastro-history-select-' + Math.random().toString(36).slice(2, 10);
    var historyLabel = create('label', { class: 'openastro-history-label openastro-load-recent-label', for: historyId }, 'Past inputs');
    var historySelect = create('select', { id: historyId, class: 'openastro-history-select openastro-load-recent-select', 'aria-label': 'Load recent past inputs' });
    var historyDefaultOption = create('option', { value: '' }, 'Past inputs...');
    historySelect.appendChild(historyDefaultOption);
    historyPanel.appendChild(historyLabel);
    historyPanel.appendChild(historySelect);
    historyWrap.appendChild(historyPanel);
    formHeader.appendChild(historyWrap);
    var name = create('input', { type: 'text', class: 'openastro-input', placeholder: 'Name (optional)' });
    var date = create('input', { type: 'date', class: 'openastro-input' });
    var time = create('input', { type: 'time', step: '60', class: 'openastro-input' });
    var city = create('input', { type: 'text', class: 'openastro-input', placeholder: 'Start typing a city' });
    var cityLoading = create('p', { class: 'openastro-city-loading' }, '');
    var cityResults = create('div', { class: 'openastro-results' });
    var includeMinorWrap = create('label', { class: 'openastro-inline' });
    var includeMinor = create('input', { type: 'checkbox', checked: 'checked' });
    includeMinorWrap.appendChild(includeMinor);
    includeMinorWrap.appendChild(document.createTextNode(' Include minor aspects'));

    var submit = create('button', { type: 'submit', class: 'openastro-btn' }, 'Generate');
    var status = create('p', { class: 'openastro-status' }, '');
    var output = create('div', { class: 'openastro-output' });

    form.appendChild(formHeader);
    form.appendChild(name);
    form.appendChild(date);
    form.appendChild(time);
    form.appendChild(city);
    form.appendChild(cityLoading);
    form.appendChild(cityResults);
    if (!simpleMode) {
      form.appendChild(includeMinorWrap);
    }
    form.appendChild(submit);
    form.appendChild(status);

    container.appendChild(form);
    container.appendChild(output);

    var cityTimer = null;
    var selectedCity = null;
    var historyEntries = getHistory();

    function refreshHistorySelect() {
      historySelect.innerHTML = '';
      historySelect.appendChild(create('option', { value: '' }, 'Past inputs...'));
      historyEntries.forEach(function (entry, idx) {
        var option = create('option', { value: String(idx) }, historyOptionLabel(entry));
        historySelect.appendChild(option);
      });
    }

    function applyHistoryEntry(entry) {
      if (!entry) return;
      name.value = entry.name || '';
      date.value = toDateValue(entry);
      time.value = toTimeValue(entry);
      var country = entry.country ? entry.country : '--';
      city.value = (entry.city_name || '') + ', ' + country;
      selectedCity = {
        name: entry.city_name || '',
        country: entry.country || '',
        timezone: entry.timezone || 'UTC',
        lat: Number(entry.lat || 0),
        lng: Number(entry.lng || 0)
      };
      if (!simpleMode) {
        includeMinor.checked = !!entry.include_minor_aspects;
      }
      cityResults.innerHTML = '';
      status.textContent = 'Loaded saved input.';
    }

    refreshHistorySelect();
    historySelect.addEventListener('change', function () {
      var idx = Number(historySelect.value);
      if (!historySelect.value || Number.isNaN(idx)) {
        return;
      }
      applyHistoryEntry(historyEntries[idx]);
    });

    city.addEventListener('input', function () {
      var q = city.value.trim();
      selectedCity = null;
      cityResults.innerHTML = '';
      cityLoading.textContent = '';
      if (cityTimer) {
        clearTimeout(cityTimer);
      }
      if (q.length < 2) {
        return;
      }

      cityLoading.textContent = 'Searching cities...';
      cityTimer = setTimeout(function () {
        api('/geo/search?q=' + encodeURIComponent(q) + '&limit=8', { method: 'GET' })
          .then(function (result) {
            cityResults.innerHTML = '';
            cityLoading.textContent = '';
            if (!result.ok || !result.body || !Array.isArray(result.body.results)) {
              cityLoading.textContent = 'Could not load city suggestions.';
              return;
            }
            if (result.body.results.length === 0) {
              cityLoading.textContent = 'No cities found. Try another query.';
              return;
            }

            result.body.results.forEach(function (item) {
              var button = create('button', { type: 'button', class: 'openastro-result-btn' });
              var countryCode = item.country || item.country_name || item.country_code || '--';
              button.textContent = item.name + ' (' + countryCode + ') · ' + item.timezone;
              button.addEventListener('click', function () {
                selectedCity = item;
                city.value = item.name + ', ' + countryCode;
                cityResults.innerHTML = '';
                cityLoading.textContent = '';
              });
              cityResults.appendChild(button);
            });
          })
          .catch(function () {
            cityResults.innerHTML = '';
            cityLoading.textContent = 'Could not load city suggestions.';
          });
      }, 300);
    });

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!date.value || !time.value || !selectedCity) {
        status.textContent = 'Date, time, and a city from suggestions are required.';
        return;
      }

      var d = date.value.split('-');
      var t = time.value.split(':');
      var payload = {
        name: name.value || '',
        year: Number(d[0]),
        month: Number(d[1]),
        day: Number(d[2]),
        hour: Number(t[0]),
        minute: Number(t[1]),
        city: selectedCity.name,
        lat: selectedCity.lat,
        lng: selectedCity.lng,
        tz_str: selectedCity.timezone
      };

      submit.disabled = true;
      status.textContent = 'Generating...';
      output.innerHTML = '';

      if (simpleMode) {
        payload.include_points = [pointForSimpleMode(mode)];
        payload.include_interpretation = includeInterpretation;
        payload.interpretation_style = interpretationStyle;

        api('/western/simple-signs', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
          .then(function (result) {
            if (!result.ok || !result.body) {
              status.textContent = (result.body && result.body.message) || 'Could not generate simple sign.';
              return;
            }

            output.innerHTML = simpleSignHtml(result.body, includeInterpretation);
            status.textContent = 'Done.';
            historyEntries = upsertHistory(makeHistoryEntry(mode, payload, selectedCity, false));
            refreshHistorySelect();
          })
          .catch(function () {
            status.textContent = 'Unexpected error while generating data.';
          })
          .finally(function () {
            submit.disabled = false;
          });

        return;
      }

      payload.include_minor_aspects = includeMinor.checked;

      Promise.all([
        api('/natal/calculate', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        }),
        api('/natal/chart-svg', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      ])
        .then(function (result) {
          var natal = result[0];
          var chart = result[1];

          if (!natal.ok || !natal.body) {
            status.textContent = 'Could not generate natal data.';
            return;
          }

          var svg = chart.ok && chart.body && chart.body.svg ? chart.body.svg : '';
          var detail = {
            request: payload,
            response: natal.body,
            chartSvg: svg || null
          };

          window.dispatchEvent(new CustomEvent('openastro:natal:generated', { detail: detail }));

          if (mode === 'chart-only') {
            output.innerHTML = chartSectionHtml(svg, cfg.report);
          } else if (mode === 'report-only') {
            output.innerHTML = reportHtml(natal.body, '', mode, cfg.report);
          } else {
            output.innerHTML = reportHtml(natal.body, svg, mode, cfg.report);
          }

          status.textContent = 'Done.';
          historyEntries = upsertHistory(makeHistoryEntry(mode, payload, selectedCity, includeMinor.checked));
          refreshHistorySelect();
        })
        .catch(function () {
          status.textContent = 'Unexpected error while generating data.';
        })
        .finally(function () {
          submit.disabled = false;
        });
    });

    output.addEventListener('click', function (event) {
      var target = event.target;
      if (!target || !target.closest) {
        return;
      }

      var button = target.closest('[data-openastro-print-chart="1"]');
      if (!button) {
        return;
      }

      event.preventDefault();
      var card = button.closest('.openastro-chart-card');
      printChartCard(card);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var nodes = document.querySelectorAll('[data-openastro-widget="1"]');
    nodes.forEach(mount);
  });
})();
