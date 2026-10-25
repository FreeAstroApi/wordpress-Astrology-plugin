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
    lilith: '⚸',
    true_lilith: '⚸',
    chiron: 'D',
    asc: '1',
    ascendant: '1',
    dsc: '2',
    descendant: '2',
    mc: '3',
    midheaven: '3',
    ic: '4'
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
  var EPHEMERIS_MONTHS = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];
  var EPHEMERIS_BODIES = [
    { id: 'sun', api: 'Sun', label: 'Sun', glyph: '☉', group: 'Core bodies' },
    { id: 'moon', api: 'Moon', label: 'Moon', glyph: '☽', group: 'Core bodies' },
    { id: 'mercury', api: 'Mercury', label: 'Mercury', glyph: '☿', group: 'Core bodies' },
    { id: 'venus', api: 'Venus', label: 'Venus', glyph: '♀', group: 'Core bodies' },
    { id: 'mars', api: 'Mars', label: 'Mars', glyph: '♂', group: 'Core bodies' },
    { id: 'jupiter', api: 'Jupiter', label: 'Jupiter', glyph: '♃', group: 'Core bodies' },
    { id: 'saturn', api: 'Saturn', label: 'Saturn', glyph: '♄', group: 'Core bodies' },
    { id: 'uranus', api: 'Uranus', label: 'Uranus', glyph: '♅', group: 'Core bodies' },
    { id: 'neptune', api: 'Neptune', label: 'Neptune', glyph: '♆', group: 'Core bodies' },
    { id: 'pluto', api: 'Pluto', label: 'Pluto', glyph: '♇', group: 'Core bodies' },
    { id: 'true_node', api: 'True Node', label: 'True Node', tableLabel: 'North Node', glyph: '☊', group: 'Nodes and lunar points' },
    { id: 'mean_node', api: 'Mean Node', label: 'Mean Node', glyph: '☊', group: 'Nodes and lunar points' },
    { id: 'lilith', api: 'Lilith', label: 'Lilith', glyph: '⚸', group: 'Nodes and lunar points' },
    { id: 'true_lilith', api: 'True Lilith', label: 'True Lilith', glyph: '⚸', group: 'Nodes and lunar points' },
    { id: 'chiron', api: 'Chiron', label: 'Chiron', glyph: '⚷', group: 'Asteroids and minor bodies' },
    { id: 'ceres', api: 'Ceres', label: 'Ceres', glyph: '⚳', group: 'Asteroids and minor bodies' },
    { id: 'pallas', api: 'Pallas', label: 'Pallas', glyph: '⚴', group: 'Asteroids and minor bodies' },
    { id: 'juno', api: 'Juno', label: 'Juno', glyph: '⚵', group: 'Asteroids and minor bodies' },
    { id: 'vesta', api: 'Vesta', label: 'Vesta', glyph: '⚶', group: 'Asteroids and minor bodies' },
    { id: 'pholus', api: 'Pholus', label: 'Pholus', glyph: 'φ', group: 'Asteroids and minor bodies' },
    { id: 'ascendant', api: 'Ascendant', label: 'Ascendant', glyph: 'ASC', group: 'Coordinate-based points', local: true },
    { id: 'mc', api: 'MC', label: 'MC', glyph: 'MC', group: 'Coordinate-based points', local: true },
    { id: 'vertex', api: 'Vertex', label: 'Vertex', glyph: 'Vx', group: 'Coordinate-based points', local: true },
    { id: 'part_of_fortune', api: 'Part of Fortune', label: 'Part of Fortune', glyph: '⊗', group: 'Coordinate-based points', local: true }
  ];
  var EPHEMERIS_DEFAULT_BODY_IDS = ['sun', 'moon', 'mercury', 'venus', 'mars', 'jupiter', 'saturn', 'uranus', 'neptune', 'pluto', 'true_node', 'chiron'];
  var ZODIAC_SIGNS = [
    { id: 'aries', label: 'Aries' },
    { id: 'taurus', label: 'Taurus' },
    { id: 'gemini', label: 'Gemini' },
    { id: 'cancer', label: 'Cancer' },
    { id: 'leo', label: 'Leo' },
    { id: 'virgo', label: 'Virgo' },
    { id: 'libra', label: 'Libra' },
    { id: 'scorpio', label: 'Scorpio' },
    { id: 'sagittarius', label: 'Sagittarius' },
    { id: 'capricorn', label: 'Capricorn' },
    { id: 'aquarius', label: 'Aquarius' },
    { id: 'pisces', label: 'Pisces' }
  ];
  var VEDIC_DIVISIONS = [
    { id: 1, label: 'D1 Rashi', short: 'D1' },
    { id: 2, label: 'D2 Hora', short: 'D2' },
    { id: 3, label: 'D3 Drekkana', short: 'D3' },
    { id: 4, label: 'D4 Chaturthamsha', short: 'D4' },
    { id: 5, label: 'D5 Panchamsha', short: 'D5' },
    { id: 7, label: 'D7 Saptamsha', short: 'D7' },
    { id: 9, label: 'D9 Navamsha', short: 'D9' },
    { id: 10, label: 'D10 Dashamsha', short: 'D10' },
    { id: 12, label: 'D12 Dwadashamsha', short: 'D12' },
    { id: 16, label: 'D16 Shodashamsha', short: 'D16' },
    { id: 20, label: 'D20 Vimshamsha', short: 'D20' },
    { id: 24, label: 'D24 Chaturvimshamsha', short: 'D24' },
    { id: 27, label: 'D27 Saptavimshamsha', short: 'D27' },
    { id: 30, label: 'D30 Trimshamsha', short: 'D30' },
    { id: 40, label: 'D40 Khavedamsha', short: 'D40' },
    { id: 45, label: 'D45 Akshavedamsha', short: 'D45' },
    { id: 60, label: 'D60 Shashtiamsha', short: 'D60' }
  ];
  var VEDIC_CALCULATION_DIVISIONS = [1, 2, 3, 7, 9, 10, 12, 30, 60];
  var VEDIC_MODULES = [
    { id: 'yogas', field: 'include_yogas', label: 'Yogas and doshas' },
    { id: 'panchang', field: 'include_panchang', label: 'Birth Panchang' },
    { id: 'shadbala', field: 'include_shadbala', label: 'Shadbala strength' },
    { id: 'ashtakavarga', field: 'include_ashtakavarga', label: 'Ashtakavarga' },
    { id: 'avastha', field: 'include_avastha', label: 'Baladi Avastha' }
  ];
  var HISTORY_KEY = 'openastro_history_v1';
  var HISTORY_LIMIT = 20;
  var TRANSLATIONS = {
    vi: {
      'Print': 'In',
      'Natal Wheel': 'Ban do sao',
      'Chart unavailable.': 'Khong co ban do sao.',
      'Name (optional)': 'Ten (khong bat buoc)',
      'Birth date': 'Ngay sinh',
      'Birth time (24-hour clock)': 'Gio sinh (dinh dang 24 gio)',
      'Birth hour (00-23)': 'Gio sinh (00-23)',
      'Hour (00-23)': 'Gio (00-23)',
      'Birth minute': 'Phut sinh',
      'Minute': 'Phut',
      "I don't know my birth time": 'Toi khong biet gio sinh',
      'Time unknown': 'Khong ro gio sinh',
      'City or city, country code': 'Thanh pho hoac thanh pho, ma quoc gia',
      'City suggestions': 'Goi y thanh pho',
      'Include minor aspects': 'Bao gom cac goc phu',
      'Generate': 'Tao',
      'Past inputs': 'Du lieu da dung',
      'Past inputs...': 'Du lieu da dung...',
      'Load recent past inputs': 'Tai du lieu gan day',
      'Calculation Access Required': 'Can quyen tinh toan',
      'Unknown city': 'Thanh pho khong ro',
      'Unknown country': 'Quoc gia khong ro',
      'Unknown': 'Khong ro',
      'Selected {city}.': 'Da chon {city}.',
      'Selected {city} - {timezone}.': 'Da chon {city} - {timezone}.',
      '{count} match for "{query}". Use arrow keys or click a city.': '{count} ket qua cho "{query}". Dung phim mui ten hoac bam vao thanh pho.',
      '{count} matches for "{query}". Use arrow keys or click a city.': '{count} ket qua cho "{query}". Dung phim mui ten hoac bam vao thanh pho.',
      'Type at least 2 characters to search cities.': 'Nhap it nhat 2 ky tu de tim thanh pho.',
      'Searching cities...': 'Dang tim thanh pho...',
      'Could not load city suggestions.': 'Khong tai duoc goi y thanh pho.',
      'No cities found. Try another query.': 'Khong tim thay thanh pho. Hay thu tu khoa khac.',
      'Loaded saved input.': 'Da tai du lieu da luu.',
      'Date, time, and a city from suggestions are required.': 'Vui long nhap ngay, gio va chon thanh pho tu danh sach goi y.',
      'Date and a city from suggestions are required.': 'Vui long nhap ngay va chon thanh pho tu danh sach goi y.',
      'Birth time is required, or choose unknown time.': 'Vui long nhap gio sinh hoac chon khong ro gio sinh.',
      'Generating...': 'Dang tao...',
      'Done.': 'Hoan tat.',
      'Done. Chart unavailable.': 'Hoan tat. Khong co ban do sao.',
      'Could not generate simple sign.': 'Khong tao duoc cung don gian.',
      'Could not generate natal chart.': 'Khong tao duoc ban do sao.',
      'Could not generate natal data.': 'Khong tao duoc du lieu la so.',
      'Unexpected error while generating data.': 'Co loi bat ngo khi tao du lieu.',
      'Natal Report': 'Bao cao la so',
      'Birth Context': 'Thong tin sinh',
      'Date': 'Ngay',
      'Time': 'Gio',
      'Place': 'Noi sinh',
      'Timezone': 'Mui gio',
      'Coordinates': 'Toa do',
      '1st House / ASC': 'Nha 1 / ASC',
      '10th House Cusp / MC': 'Dinh nha 10 / MC',
      'Sign:': 'Cung:',
      'House:': 'Nha:',
      'Ascendant interpretation was not returned for this chart.': 'API khong tra ve dien giai Ascendant cho la so nay.',
      'Midheaven interpretation was not returned for this chart.': 'API khong tra ve dien giai Midheaven cho la so nay.',
      'Planet Interpretations': 'Dien giai hanh tinh',
      'Interpretation not available for this planet.': 'Chua co dien giai cho hanh tinh nay.',
      'All Aspect Dynamics': 'Tat ca dong luc goc chieu',
      'P1': 'P1',
      'Aspect': 'Goc',
      'P2': 'P2',
      'Orb': 'Orb',
      'Major': 'Chinh',
      'Yes': 'Co',
      'No': 'Khong',
      '{count} aspects detected.': 'Phat hien {count} goc chieu.',
      '{point} Sign': 'Cung {point}',
      'Interpretation': 'Dien giai',
      'Interpretation was not returned for this point.': 'API khong tra ve dien giai cho diem nay.',
      'aspect_type_conjunction': 'dong hanh',
      'aspect_type_opposition': 'doi dinh',
      'aspect_type_trine': 'tam hop',
      'aspect_type_square': 'vuong goc',
      'aspect_type_sextile': 'luc hop',
      'aspect_type_quincunx': 'quincunx'
    },
    fr: {
      'Print': 'Imprimer',
      'Natal Wheel': 'Theme natal',
      'Chart unavailable.': 'Theme indisponible.',
      'Name (optional)': 'Nom (facultatif)',
      'Birth date': 'Date de naissance',
      'Birth time (24-hour clock)': 'Heure de naissance (format 24 heures)',
      'Birth hour (00-23)': 'Heure de naissance (00-23)',
      'Hour (00-23)': 'Heure (00-23)',
      'Birth minute': 'Minute de naissance',
      'Minute': 'Minute',
      "I don't know my birth time": 'Je ne connais pas mon heure de naissance',
      'Time unknown': 'Heure inconnue',
      'City or city, country code': 'Ville ou ville, code pays',
      'City suggestions': 'Suggestions de villes',
      'Include minor aspects': 'Inclure les aspects mineurs',
      'Generate': 'Generer',
      'Past inputs': 'Saisies recentes',
      'Past inputs...': 'Saisies recentes...',
      'Load recent past inputs': 'Charger les saisies recentes',
      'Calculation Access Required': 'Acces au calcul requis',
      'Selected {city}.': '{city} selectionnee.',
      'Selected {city} - {timezone}.': '{city} selectionnee - {timezone}.',
      'Type at least 2 characters to search cities.': 'Saisissez au moins 2 caracteres pour chercher une ville.',
      'Searching cities...': 'Recherche des villes...',
      'Could not load city suggestions.': 'Impossible de charger les suggestions de villes.',
      'No cities found. Try another query.': 'Aucune ville trouvee. Essayez une autre recherche.',
      'Loaded saved input.': 'Saisie enregistree chargee.',
      'Date, time, and a city from suggestions are required.': 'La date, l heure et une ville suggeree sont requises.',
      'Date and a city from suggestions are required.': 'La date et une ville suggeree sont requises.',
      'Birth time is required, or choose unknown time.': 'L heure de naissance est requise, ou choisissez heure inconnue.',
      'Generating...': 'Generation...',
      'Done.': 'Termine.',
      'Done. Chart unavailable.': 'Termine. Theme indisponible.',
      'Natal Report': 'Rapport natal',
      'Birth Context': 'Contexte de naissance',
      'Date': 'Date',
      'Time': 'Heure',
      'Place': 'Lieu',
      'Timezone': 'Fuseau horaire',
      'Coordinates': 'Coordonnees',
      'Planet Interpretations': 'Interpretations des planetes',
      'All Aspect Dynamics': 'Dynamiques des aspects',
      'Major': 'Majeur',
      'Yes': 'Oui',
      'No': 'Non',
      'Interpretation': 'Interpretation',
      'aspect_type_conjunction': 'conjonction',
      'aspect_type_opposition': 'opposition',
      'aspect_type_trine': 'trigone',
      'aspect_type_square': 'carre',
      'aspect_type_sextile': 'sextile',
      'aspect_type_quincunx': 'quinconce'
    },
    es: {
      'Print': 'Imprimir',
      'Natal Wheel': 'Carta natal',
      'Chart unavailable.': 'Carta no disponible.',
      'Name (optional)': 'Nombre (opcional)',
      'Birth date': 'Fecha de nacimiento',
      'Birth time (24-hour clock)': 'Hora de nacimiento (formato de 24 horas)',
      'Birth hour (00-23)': 'Hora de nacimiento (00-23)',
      'Hour (00-23)': 'Hora (00-23)',
      'Birth minute': 'Minuto de nacimiento',
      'Minute': 'Minuto',
      "I don't know my birth time": 'No conozco mi hora de nacimiento',
      'Time unknown': 'Hora desconocida',
      'City or city, country code': 'Ciudad o ciudad, codigo de pais',
      'City suggestions': 'Sugerencias de ciudad',
      'Include minor aspects': 'Incluir aspectos menores',
      'Generate': 'Generar',
      'Past inputs': 'Datos recientes',
      'Past inputs...': 'Datos recientes...',
      'Load recent past inputs': 'Cargar datos recientes',
      'Calculation Access Required': 'Acceso de calculo requerido',
      'Selected {city}.': '{city} seleccionada.',
      'Selected {city} - {timezone}.': '{city} seleccionada - {timezone}.',
      'Type at least 2 characters to search cities.': 'Escribe al menos 2 caracteres para buscar ciudades.',
      'Searching cities...': 'Buscando ciudades...',
      'Could not load city suggestions.': 'No se pudieron cargar sugerencias de ciudad.',
      'No cities found. Try another query.': 'No se encontraron ciudades. Prueba otra busqueda.',
      'Loaded saved input.': 'Datos guardados cargados.',
      'Date, time, and a city from suggestions are required.': 'Se requiere fecha, hora y una ciudad sugerida.',
      'Date and a city from suggestions are required.': 'Se requiere fecha y una ciudad sugerida.',
      'Birth time is required, or choose unknown time.': 'Se requiere hora de nacimiento, o elige hora desconocida.',
      'Generating...': 'Generando...',
      'Done.': 'Listo.',
      'Done. Chart unavailable.': 'Listo. Carta no disponible.',
      'Natal Report': 'Informe natal',
      'Birth Context': 'Contexto de nacimiento',
      'Date': 'Fecha',
      'Time': 'Hora',
      'Place': 'Lugar',
      'Timezone': 'Zona horaria',
      'Coordinates': 'Coordenadas',
      'Planet Interpretations': 'Interpretaciones planetarias',
      'All Aspect Dynamics': 'Dinamicas de aspectos',
      'Major': 'Mayor',
      'Yes': 'Si',
      'No': 'No',
      'Interpretation': 'Interpretacion',
      'aspect_type_conjunction': 'conjuncion',
      'aspect_type_opposition': 'oposicion',
      'aspect_type_trine': 'trigono',
      'aspect_type_square': 'cuadratura',
      'aspect_type_sextile': 'sextil',
      'aspect_type_quincunx': 'quincuncio'
    },
    de: {
      'Print': 'Drucken',
      'Natal Wheel': 'Radix',
      'Chart unavailable.': 'Horoskop nicht verfuegbar.',
      'Name (optional)': 'Name (optional)',
      'Birth date': 'Geburtsdatum',
      'Birth time (24-hour clock)': 'Geburtszeit (24-Stunden-Format)',
      'Birth hour (00-23)': 'Geburtsstunde (00-23)',
      'Hour (00-23)': 'Stunde (00-23)',
      'Birth minute': 'Geburtsminute',
      'Minute': 'Minute',
      "I don't know my birth time": 'Ich kenne meine Geburtszeit nicht',
      'Time unknown': 'Geburtszeit unbekannt',
      'City or city, country code': 'Stadt oder Stadt, Laendercode',
      'City suggestions': 'Stadtvorschlaege',
      'Include minor aspects': 'Nebenaspekte einbeziehen',
      'Generate': 'Erstellen',
      'Past inputs': 'Fruehere Eingaben',
      'Past inputs...': 'Fruehere Eingaben...',
      'Load recent past inputs': 'Fruehere Eingaben laden',
      'Calculation Access Required': 'Berechnungszugriff erforderlich',
      'Selected {city}.': '{city} ausgewaehlt.',
      'Selected {city} - {timezone}.': '{city} ausgewaehlt - {timezone}.',
      'Type at least 2 characters to search cities.': 'Geben Sie mindestens 2 Zeichen ein, um Staedte zu suchen.',
      'Searching cities...': 'Staedte werden gesucht...',
      'Could not load city suggestions.': 'Stadtvorschlaege konnten nicht geladen werden.',
      'No cities found. Try another query.': 'Keine Staedte gefunden. Versuchen Sie eine andere Suche.',
      'Loaded saved input.': 'Gespeicherte Eingabe geladen.',
      'Date, time, and a city from suggestions are required.': 'Datum, Uhrzeit und eine Stadt aus den Vorschlaegen sind erforderlich.',
      'Date and a city from suggestions are required.': 'Datum und eine Stadt aus den Vorschlaegen sind erforderlich.',
      'Birth time is required, or choose unknown time.': 'Geburtszeit ist erforderlich, oder waehlen Sie unbekannte Zeit.',
      'Generating...': 'Wird erstellt...',
      'Done.': 'Fertig.',
      'Done. Chart unavailable.': 'Fertig. Horoskop nicht verfuegbar.',
      'Natal Report': 'Geburtsbericht',
      'Birth Context': 'Geburtskontext',
      'Date': 'Datum',
      'Time': 'Uhrzeit',
      'Place': 'Ort',
      'Timezone': 'Zeitzone',
      'Coordinates': 'Koordinaten',
      'Planet Interpretations': 'Planeten-Deutungen',
      'All Aspect Dynamics': 'Alle Aspektdynamiken',
      'Major': 'Hauptaspekt',
      'Yes': 'Ja',
      'No': 'Nein',
      'Interpretation': 'Deutung',
      'aspect_type_conjunction': 'Konjunktion',
      'aspect_type_opposition': 'Opposition',
      'aspect_type_trine': 'Trigon',
      'aspect_type_square': 'Quadrat',
      'aspect_type_sextile': 'Sextil',
      'aspect_type_quincunx': 'Quinkunx'
    },
    pt: {
      'Print': 'Imprimir',
      'Natal Wheel': 'Mapa natal',
      'Chart unavailable.': 'Mapa indisponivel.',
      'Name (optional)': 'Nome (opcional)',
      'Birth date': 'Data de nascimento',
      'Birth time (24-hour clock)': 'Hora de nascimento (formato de 24 horas)',
      'Birth hour (00-23)': 'Hora de nascimento (00-23)',
      'Hour (00-23)': 'Hora (00-23)',
      'Birth minute': 'Minuto de nascimento',
      'Minute': 'Minuto',
      "I don't know my birth time": 'Nao sei minha hora de nascimento',
      'Time unknown': 'Hora desconhecida',
      'City or city, country code': 'Cidade ou cidade, codigo do pais',
      'City suggestions': 'Sugestoes de cidade',
      'Include minor aspects': 'Incluir aspectos menores',
      'Generate': 'Gerar',
      'Past inputs': 'Entradas recentes',
      'Past inputs...': 'Entradas recentes...',
      'Load recent past inputs': 'Carregar entradas recentes',
      'Calculation Access Required': 'Acesso de calculo necessario',
      'Selected {city}.': '{city} selecionada.',
      'Selected {city} - {timezone}.': '{city} selecionada - {timezone}.',
      'Type at least 2 characters to search cities.': 'Digite pelo menos 2 caracteres para buscar cidades.',
      'Searching cities...': 'Buscando cidades...',
      'Could not load city suggestions.': 'Nao foi possivel carregar sugestoes de cidade.',
      'No cities found. Try another query.': 'Nenhuma cidade encontrada. Tente outra busca.',
      'Loaded saved input.': 'Entrada salva carregada.',
      'Date, time, and a city from suggestions are required.': 'Data, hora e uma cidade sugerida sao obrigatorias.',
      'Date and a city from suggestions are required.': 'Data e uma cidade sugerida sao obrigatorias.',
      'Birth time is required, or choose unknown time.': 'A hora de nascimento e obrigatoria, ou escolha hora desconhecida.',
      'Generating...': 'Gerando...',
      'Done.': 'Concluido.',
      'Done. Chart unavailable.': 'Concluido. Mapa indisponivel.',
      'Natal Report': 'Relatorio natal',
      'Birth Context': 'Contexto de nascimento',
      'Date': 'Data',
      'Time': 'Hora',
      'Place': 'Local',
      'Timezone': 'Fuso horario',
      'Coordinates': 'Coordenadas',
      'Planet Interpretations': 'Interpretacoes planetarias',
      'All Aspect Dynamics': 'Dinamicas dos aspectos',
      'Major': 'Maior',
      'Yes': 'Sim',
      'No': 'Nao',
      'Interpretation': 'Interpretacao',
      'aspect_type_conjunction': 'conjuncao',
      'aspect_type_opposition': 'oposicao',
      'aspect_type_trine': 'trigono',
      'aspect_type_square': 'quadratura',
      'aspect_type_sextile': 'sextil',
      'aspect_type_quincunx': 'quincuncio'
    }
  };

  function normalizeLanguageCode(value) {
    var lang = String(value || '').toLowerCase().replace('_', '-');
    if (!lang || lang === 'auto') {
      return 'auto';
    }
    lang = lang.split('-')[0];
    return (TRANSLATIONS[lang] || lang === 'en') ? lang : '';
  }

  function detectBrowserLanguage() {
    var htmlLang = document.documentElement ? document.documentElement.getAttribute('lang') : '';
    var detected = normalizeLanguageCode(htmlLang);
    if (detected && detected !== 'auto') {
      return detected;
    }
    if (navigator.languages && navigator.languages.length) {
      for (var i = 0; i < navigator.languages.length; i += 1) {
        detected = normalizeLanguageCode(navigator.languages[i]);
        if (detected && detected !== 'auto') {
          return detected;
        }
      }
    }
    detected = normalizeLanguageCode(navigator.language || '');
    return detected && detected !== 'auto' ? detected : 'en';
  }

  function resolveLanguage(value) {
    var lang = normalizeLanguageCode(value);
    if (!lang || lang === 'auto') {
      return detectBrowserLanguage();
    }
    return lang;
  }

  function t(key, lang) {
    var code = resolveLanguage(lang || 'en');
    if (code !== 'en' && TRANSLATIONS[code] && Object.prototype.hasOwnProperty.call(TRANSLATIONS[code], key)) {
      return TRANSLATIONS[code][key];
    }
    return key;
  }

  function fmt(key, vars, lang) {
    var text = t(key, lang);
    Object.keys(vars || {}).forEach(function (name) {
      text = text.replace(new RegExp('\\{' + name + '\\}', 'g'), vars[name]);
    });
    return text;
  }

  function getCfg() {
    var cfg = window.AstrologySuiteBlocks || window.OpenAstroBlocks || { root: '/wp-json/astrology-suite/v1/', nonce: '', language: 'auto', permissions: {}, report: {}, style: {}, ephemerisStyle: {}, horoscope: {}, horoscopeStyle: {}, vedicStyle: {}, botStyle: {}, typography: {} };
    cfg.permissions = cfg.permissions || {};
    cfg.report = cfg.report || {};
    cfg.style = cfg.style || {};
    cfg.ephemerisStyle = cfg.ephemerisStyle || {};
    cfg.horoscope = cfg.horoscope || {};
    cfg.horoscopeStyle = cfg.horoscopeStyle || {};
    cfg.vedicStyle = cfg.vedicStyle || {};
    cfg.botStyle = cfg.botStyle || {};
    cfg.typography = cfg.typography || {};
    cfg.language = cfg.language || 'auto';
    cfg.permissions.canCalculate = !!cfg.permissions.canCalculate;
    cfg.permissions.calculationMessage = cfg.permissions.calculationMessage || 'This astrology form is disabled for public visitors. Enable public calculations in Astrology Suite API settings, or log in with permission to edit posts.';
    cfg.report.detailLevel = cfg.report.detailLevel === 'concise' ? 'concise' : 'full';
    cfg.report.maxPlanets = Number(cfg.report.maxPlanets || 13);
    cfg.report.maxAspects = Number(cfg.report.maxAspects || 40);
    cfg.report.showTables = cfg.report.showTables !== false;
    cfg.report.showAngles = cfg.report.showAngles !== false;
    cfg.report.showBirthContext = cfg.report.showBirthContext !== false;
    cfg.report.showAspectLinks = cfg.report.showAspectLinks !== false && cfg.report.showPersonalizedNotes !== false;
    cfg.report.showAspectGlyphs = cfg.report.showAspectGlyphs !== false;
    cfg.report.showThematicInterpretation = !!cfg.report.showThematicInterpretation;
    cfg.report.showPrintButton = cfg.report.showPrintButton !== false;
    cfg.style.showBorder = cfg.style.showBorder !== false;
    cfg.style.backgroundColor = cfg.style.backgroundColor || '#ffffff';
    cfg.style.transparentBackground = !!cfg.style.transparentBackground;
    cfg.style.textColor = cfg.style.textColor || '#111111';
    cfg.style.inputBorderRadius = Number(cfg.style.inputBorderRadius || 6);
    cfg.style.inputBorderColor = cfg.style.inputBorderColor || '#d1d5db';
    cfg.style.inputBgColor = cfg.style.inputBgColor || '#ffffff';
    cfg.style.inputTextColor = cfg.style.inputTextColor || '#111111';
    cfg.style.inputPlaceholderColor = cfg.style.inputPlaceholderColor || '#6b7280';
    cfg.style.buttonBorderRadius = Number(cfg.style.buttonBorderRadius || 6);
    cfg.style.buttonBgColor = cfg.style.buttonBgColor || '#111827';
    cfg.style.buttonTextColor = cfg.style.buttonTextColor || '#ffffff';
    cfg.style.buttonBorderColor = cfg.style.buttonBorderColor || '#111827';
    cfg.style.labelColor = cfg.style.labelColor || '#111111';
    cfg.style.labelWeight = cfg.style.labelWeight || '600';
    cfg.style.focusRingColor = cfg.style.focusRingColor || '#2563eb';
    cfg.style.astroFontSize = Number(cfg.style.astroFontSize || 20);
    cfg.style.astroFontColor = cfg.style.astroFontColor || '#111111';
    cfg.style.modalBgColor = cfg.style.modalBgColor || '#ffffff';
    cfg.style.modalTextColor = cfg.style.modalTextColor || '#111111';
    cfg.style.modalBorderColor = cfg.style.modalBorderColor || '#111111';
    cfg.style.modalAccentColor = cfg.style.modalAccentColor || '#d97706';
    cfg.style.showHistory = cfg.style.showHistory !== false;
    cfg.style.historyBgColor = cfg.style.historyBgColor || '#f9fafb';
    cfg.style.historyBorderColor = cfg.style.historyBorderColor || '#e5e7eb';
    cfg.style.historyTextColor = cfg.style.historyTextColor || '#111111';
    cfg.ephemerisStyle.tableBgColor = cfg.ephemerisStyle.tableBgColor || '#ffffff';
    cfg.ephemerisStyle.tableTextColor = cfg.ephemerisStyle.tableTextColor || '#111111';
    cfg.ephemerisStyle.tableBorderColor = cfg.ephemerisStyle.tableBorderColor || '#e5e7eb';
    cfg.ephemerisStyle.headerBgColor = cfg.ephemerisStyle.headerBgColor || '#f8fafc';
    cfg.ephemerisStyle.headerTextColor = cfg.ephemerisStyle.headerTextColor || '#111111';
    cfg.ephemerisStyle.badgeBgColor = cfg.ephemerisStyle.badgeBgColor || '#f8fafc';
    cfg.ephemerisStyle.badgeTextColor = cfg.ephemerisStyle.badgeTextColor || '#475569';
    cfg.ephemerisStyle.todayBgColor = cfg.ephemerisStyle.todayBgColor || '#f8fafc';
    cfg.ephemerisStyle.todayAccentColor = cfg.ephemerisStyle.todayAccentColor || '#2563eb';
    cfg.ephemerisStyle.retrogradeColor = cfg.ephemerisStyle.retrogradeColor || '#b91c1c';
    cfg.ephemerisStyle.ingressColor = cfg.ephemerisStyle.ingressColor || '#1d4ed8';
    cfg.ephemerisStyle.stationColor = cfg.ephemerisStyle.stationColor || '#a16207';
    cfg.horoscope.showTheme = cfg.horoscope.showTheme !== false;
    cfg.horoscope.showSummary = cfg.horoscope.showSummary !== false;
    cfg.horoscope.showScores = cfg.horoscope.showScores !== false;
    cfg.horoscope.showScoreFactors = cfg.horoscope.showScoreFactors !== false;
    cfg.horoscope.showPersonalContext = cfg.horoscope.showPersonalContext !== false;
    cfg.horoscope.showLucky = cfg.horoscope.showLucky !== false;
    cfg.horoscope.showGuidance = cfg.horoscope.showGuidance !== false;
    cfg.horoscope.showAstro = cfg.horoscope.showAstro !== false;
    cfg.horoscopeStyle.cardBgColor = cfg.horoscopeStyle.cardBgColor || '#ffffff';
    cfg.horoscopeStyle.cardTextColor = cfg.horoscopeStyle.cardTextColor || '#111111';
    cfg.horoscopeStyle.cardBorderColor = cfg.horoscopeStyle.cardBorderColor || '#e5e7eb';
    cfg.horoscopeStyle.themeBgColor = cfg.horoscopeStyle.themeBgColor || '#111827';
    cfg.horoscopeStyle.themeTextColor = cfg.horoscopeStyle.themeTextColor || '#ffffff';
    cfg.horoscopeStyle.chipBgColor = cfg.horoscopeStyle.chipBgColor || '#f9fafb';
    cfg.horoscopeStyle.chipTextColor = cfg.horoscopeStyle.chipTextColor || '#111111';
    cfg.horoscopeStyle.chipBorderColor = cfg.horoscopeStyle.chipBorderColor || '#e5e7eb';
    cfg.horoscopeStyle.tileBgColor = cfg.horoscopeStyle.tileBgColor || '#f9fafb';
    cfg.horoscopeStyle.tileTextColor = cfg.horoscopeStyle.tileTextColor || '#111111';
    cfg.horoscopeStyle.tileBorderColor = cfg.horoscopeStyle.tileBorderColor || '#e5e7eb';
    cfg.horoscopeStyle.accentColor = cfg.horoscopeStyle.accentColor || '#2563eb';
    cfg.vedicStyle.surfaceBgColor = cfg.vedicStyle.surfaceBgColor || '#fffdf9';
    cfg.vedicStyle.surfaceTextColor = cfg.vedicStyle.surfaceTextColor || '#29211c';
    cfg.vedicStyle.surfaceBorderColor = cfg.vedicStyle.surfaceBorderColor || '#e7ddd2';
    cfg.vedicStyle.accentColor = cfg.vedicStyle.accentColor || '#ac3b32';
    cfg.vedicStyle.tileBgColor = cfg.vedicStyle.tileBgColor || '#f8f2ea';
    cfg.vedicStyle.tileTextColor = cfg.vedicStyle.tileTextColor || '#29211c';
    cfg.vedicStyle.tileBorderColor = cfg.vedicStyle.tileBorderColor || '#e5d6c6';
    cfg.vedicStyle.tableHeaderBgColor = cfg.vedicStyle.tableHeaderBgColor || '#efe3d5';
    cfg.vedicStyle.tableHeaderTextColor = cfg.vedicStyle.tableHeaderTextColor || '#4b3025';
    cfg.vedicStyle.badgeBgColor = cfg.vedicStyle.badgeBgColor || '#f9e2dd';
    cfg.vedicStyle.badgeTextColor = cfg.vedicStyle.badgeTextColor || '#8f251d';
    cfg.botStyle.shellBgColor = cfg.botStyle.shellBgColor || '#101014';
    cfg.botStyle.headerBgColor = cfg.botStyle.headerBgColor || '#17231f';
    cfg.botStyle.screenBgColor = cfg.botStyle.screenBgColor || '#0f1f1b';
    cfg.botStyle.accentColor = cfg.botStyle.accentColor || '#facc15';
    cfg.botStyle.userBubbleBgColor = cfg.botStyle.userBubbleBgColor || '#d8f7c5';
    cfg.botStyle.userBubbleTextColor = cfg.botStyle.userBubbleTextColor || '#142117';
    cfg.botStyle.assistantBubbleBgColor = cfg.botStyle.assistantBubbleBgColor || '#202c28';
    cfg.botStyle.assistantBubbleTextColor = cfg.botStyle.assistantBubbleTextColor || '#f1f5f9';
    cfg.typography.h2 = Number(cfg.typography.h2 || 28);
    cfg.typography.h3 = Number(cfg.typography.h3 || 22);
    cfg.typography.h4 = Number(cfg.typography.h4 || 18);
    cfg.typography.body = Number(cfg.typography.body || 16);
    cfg.typography.table = Number(cfg.typography.table || 14);
    cfg.typography.input = Number(cfg.typography.input || 16);
    cfg.typography.button = Number(cfg.typography.button || 16);
    cfg.typography.meta = Number(cfg.typography.meta || 13);
    return cfg;
  }

  function chartSectionHtml(svg, reportCfg, lang) {
    var printButton = reportCfg.showPrintButton
      ? '<button type="button" class="' + expandClassNames('openastro-print-btn') + '" data-openastro-print-chart="1">' + esc(t('Print', lang)) + '</button>'
      : '';
    return '<section class="' + expandClassNames('openastro-card openastro-chart-card') + '"><h3>' + esc(t('Natal Wheel', lang)) + '</h3>' + printButton + '<div class="' + expandClassNames('openastro-chart') + '">' + (svg || '<p>' + esc(t('Chart unavailable.', lang)) + '</p>') + '</div></section>';
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

  function simpleSignHtml(result, includeInterpretation, lang) {
    var point = prettyPoint(result.point || '');
    var sign = result.sign || result.sign_id || t('Unknown', lang);
    var signGlyph = glyphForSign(sign);
    var degree = typeof result.degree === 'number' ? result.degree.toFixed(2) + '°' : '';
    var interp = result.interpretation && result.interpretation.body ? result.interpretation.body : '';

    var html = '<section class="' + expandClassNames('openastro-card openastro-simple-sign-card') + '">';
    html += '<h3>' + esc(fmt('{point} Sign', { point: point }, lang)) + '</h3>';
    html += '<p class="' + expandClassNames('openastro-simple-sign-value') + '">' + glyphSpan(signGlyph) + esc(sign) + (degree ? ' · ' + esc(degree) : '') + '</p>';

    if (includeInterpretation) {
      html += '<div class="' + expandClassNames('openastro-simple-sign-interpretation') + '">';
      html += '<h4>' + esc(t('Interpretation', lang)) + '</h4>';
      html += '<p>' + esc(interp || t('Interpretation was not returned for this point.', lang)) + '</p>';
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

  function expandClassNames(value) {
    return String(value || '').split(/\s+/).filter(Boolean).reduce(function (classes, item) {
      if (item.indexOf('openastro-') === 0) {
        classes.push(item.replace('openastro-', 'astrology-suite-'));
      }
      classes.push(item);
      return classes;
    }, []).join(' ');
  }

  function create(tag, attrs, text) {
    var node = document.createElement(tag);
    if (attrs) {
      Object.keys(attrs).forEach(function (k) {
        if (k === 'checked') {
          node.checked = !!attrs[k];
          return;
        }
        if (k === 'class') {
          node.setAttribute(k, expandClassNames(attrs[k]));
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
    return '<span class="' + expandClassNames('openastro-astro-glyph') + '">' + esc(char) + '</span>';
  }

  function bodyGlyphHtml(value) {
    var glyph = glyphForBody(value);
    if (!glyph) {
      return '';
    }
    return glyphSpan(glyph);
  }

  function api(path, options, attempt) {
    var cfg = getCfg();
    var retryAttempt = Number(attempt || 0);
    var headers = options.headers || {};
    if (cfg.nonce) {
      headers['X-WP-Nonce'] = cfg.nonce;
    }

    return fetch(cfg.root.replace(/\/$/, '') + path, Object.assign({}, options, { headers: headers }))
      .then(function (r) {
        return r.json().then(function (body) {
          return { ok: r.ok, body: body };
        });
      })
      .then(function (result) {
        var data = result.body && result.body.data && typeof result.body.data === 'object' ? result.body.data : {};
        var rateType = String(data.rate_limit_type || '');
        var retryMs = Number(data.retry_after_ms || 0);
        var retryable = rateType === 'site_rps' || rateType === 'rps';

        if (!result.ok && retryable && retryMs > 0 && retryMs <= 5000 && retryAttempt < 3) {
          var jitterMs = 25 + Math.floor(Math.random() * 100);
          return new Promise(function (resolve) {
            window.setTimeout(resolve, retryMs + jitterMs);
          }).then(function () {
            return api(path, options, retryAttempt + 1);
          });
        }

        return result;
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
    if (entry && entry.time_known === false) {
      return '';
    }
    return pad2(entry.hour) + ':' + pad2(entry.minute);
  }

  function createTimeControls(lang) {
    var group = create('div', { class: 'openastro-time-field' });
    var label = create('span', { class: 'openastro-time-label' }, t('Birth time (24-hour clock)', lang));
    var wrap = create('div', { class: 'openastro-time-row' });
    var hour = create('select', { class: 'openastro-input openastro-time-hour', 'aria-label': t('Birth hour (00-23)', lang) });
    var minute = create('select', { class: 'openastro-input openastro-time-minute', 'aria-label': t('Birth minute', lang) });

    hour.appendChild(create('option', { value: '' }, t('Hour (00-23)', lang)));
    minute.appendChild(create('option', { value: '' }, t('Minute', lang)));

    for (var h = 0; h <= 23; h += 1) {
      hour.appendChild(create('option', { value: pad2(h) }, pad2(h)));
    }

    for (var m = 0; m <= 59; m += 1) {
      minute.appendChild(create('option', { value: pad2(m) }, pad2(m)));
    }

    wrap.appendChild(hour);
    wrap.appendChild(minute);
    group.appendChild(label);
    group.appendChild(wrap);

    return {
      wrap: group,
      hour: hour,
      minute: minute,
      value: function () {
        if (!hour.value || !minute.value) {
          return '';
        }
        return hour.value + ':' + minute.value;
      },
      setValue: function (value) {
        var parts = String(value || '').split(':');
        hour.value = /^\d{1,2}$/.test(parts[0] || '') ? pad2(parts[0]) : '';
        minute.value = /^\d{1,2}$/.test(parts[1] || '') ? pad2(parts[1]) : '';
      },
      setDisabled: function (disabled) {
        hour.disabled = !!disabled;
        minute.disabled = !!disabled;
        group.classList.toggle('is-disabled', !!disabled);
      }
    };
  }

  function normalizeNum(v, fixed) {
    var num = Number(v || 0);
    return num.toFixed(fixed || 4);
  }

  function historyScalar(value) {
    return value === null || typeof value === 'undefined' ? '' : String(value);
  }

  function historyIdFromEntry(entry) {
    return [
      historyScalar(entry.year),
      historyScalar(entry.month),
      historyScalar(entry.day),
      entry.time_known === false ? 'unknown' : 'known',
      historyScalar(entry.hour),
      historyScalar(entry.minute),
      normalizeNum(entry.lat, 4),
      normalizeNum(entry.lng, 4)
    ].join('|');
  }

  function makeHistoryEntry(mode, payload, selectedCity, includeMinor) {
    var timeKnown = payload.time_known !== false;
    var country = selectedCity.country || selectedCity.country_name || selectedCity.country_code || '';
    var entry = {
      saved_at: new Date().toISOString(),
      name: payload.name || '',
      year: Number(payload.year || 0),
      month: Number(payload.month || 0),
      day: Number(payload.day || 0),
      time_known: timeKnown,
      hour: timeKnown ? Number(payload.hour || 0) : null,
      minute: timeKnown ? Number(payload.minute || 0) : null,
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

  function historyOptionLabel(entry, lang) {
    var datePart = [pad2(entry.day), pad2(entry.month), String(entry.year)].join('/');
    var timePart = entry.time_known === false ? t('Time unknown', lang) : [pad2(entry.hour), pad2(entry.minute)].join(':');
    var place = entry.city_name || 'Unknown city';
    var country = entry.country ? ' (' + entry.country + ')' : '';
    var person = String(entry.name || '').trim();
    var personPrefix = person ? person + ' - ' : '';
    return personPrefix + datePart + ' ' + timePart + ' - ' + place + country;
  }

  function normalizeSearchText(value) {
    var text = String(value || '');
    if (typeof text.normalize === 'function') {
      text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }
    return text.toLowerCase();
  }

  function parseCitySearchInput(value) {
    var query = String(value || '').trim();
    var country = '';
    var commaParts = query.split(',');
    var possibleCountry = commaParts.length > 1 ? commaParts[commaParts.length - 1].trim() : '';
    var trailingCountry = query.match(/\s+([A-Za-z]{2})$/);

    if (/^[A-Za-z]{2}$/.test(possibleCountry)) {
      country = possibleCountry.toUpperCase();
      query = commaParts.slice(0, -1).join(',').trim();
    } else if (trailingCountry) {
      country = trailingCountry[1].toUpperCase();
      query = query.slice(0, trailingCountry.index).trim();
    }

    return {
      query: query,
      country: country
    };
  }

  function cityCountryCode(item) {
    var code = item.country_code || item.country || '';
    if (/^[A-Za-z]{2,3}$/.test(String(code))) {
      return String(code).toUpperCase();
    }
    return code;
  }

  function cityCountryName(item) {
    return item.country_name || item.country_full_name || '';
  }

  function cityCountryLabel(item) {
    var code = cityCountryCode(item);
    var name = cityCountryName(item);
    if (name && code && normalizeSearchText(name) !== normalizeSearchText(code)) {
      return name + ' (' + code + ')';
    }
    return code || name || 'Unknown country';
  }

  function cityValueLabel(item) {
    var code = cityCountryCode(item);
    var country = /^[A-Z]{2,3}$/.test(String(code)) ? code : cityCountryName(item);
    return (item.name || '') + (country ? ', ' + country : '');
  }

  function cityTimezoneLabel(item) {
    return item.timezone || item.tz_str || '';
  }

  function cityRegionLabel(item) {
    return item.admin1_name || item.admin_name || item.region || item.state || item.province || '';
  }

  function cityCoordinateLabel(item) {
    var lat = Number(item.lat);
    var lng = Number(item.lng);
    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
      return '';
    }
    return lat.toFixed(2) + ', ' + lng.toFixed(2);
  }

  function cityMetaParts(item) {
    var parts = [];
    var region = cityRegionLabel(item);
    var countryName = cityCountryName(item);
    var timezone = cityTimezoneLabel(item);
    var coordinates = cityCoordinateLabel(item);

    if (region && normalizeSearchText(region) !== normalizeSearchText(item.name)) {
      parts.push(region);
    }
    if (countryName && normalizeSearchText(countryName) !== normalizeSearchText(cityCountryCode(item))) {
      parts.push(countryName);
    }
    if (timezone) {
      parts.push(timezone);
    }
    if (coordinates) {
      parts.push(coordinates);
    }

    return parts;
  }

  function citySuggestionLabel(item) {
    var label = [item.name || 'Unknown city', cityCountryLabel(item)].filter(Boolean).join(', ');
    var meta = cityMetaParts(item);
    return meta.length ? label + ', ' + meta.join(', ') : label;
  }

  function citySearchRank(item, query) {
    var q = normalizeSearchText(query);
    var name = normalizeSearchText(item.name);
    var countryCode = normalizeSearchText(cityCountryCode(item));
    var countryName = normalizeSearchText(cityCountryName(item));
    var words = name.split(/[\s\-_,.]+/);

    if (name === q) return 0;
    if (name.indexOf(q) === 0) return 1;
    for (var i = 0; i < words.length; i += 1) {
      if (words[i].indexOf(q) === 0) return 2;
    }
    if (countryCode === q) return 3;
    if (name.indexOf(q) !== -1) return 4;
    if (countryName.indexOf(q) !== -1) return 5;
    return 6;
  }

  function sortCitySuggestions(items, query) {
    return items.slice().sort(function (a, b) {
      var rankDiff = citySearchRank(a, query) - citySearchRank(b, query);
      if (rankDiff !== 0) return rankDiff;
      var populationDiff = Number(b.population || 0) - Number(a.population || 0);
      if (populationDiff !== 0) return populationDiff;
      var aName = String(a.name || '');
      var bName = String(b.name || '');
      if (aName.length !== bName.length) return aName.length - bName.length;
      return aName.localeCompare(bName);
    });
  }

  function setupCityAutocomplete(options) {
    var input = options.input;
    var wrap = options.wrap;
    var results = options.results;
    var message = options.message;
    var resultsId = options.resultsId;
    var onSelect = typeof options.onSelect === 'function' ? options.onSelect : function () {};
    var timer = null;
    var requestId = 0;
    var suggestions = [];
    var activeIndex = -1;

    function setExpanded(expanded) {
      var isExpanded = expanded && suggestions.length;
      input.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
      wrap.classList.toggle('is-open', !!isExpanded);
      if (!expanded) {
        input.removeAttribute('aria-activedescendant');
      }
    }

    function setMessage(text) {
      message.textContent = text || '';
    }

    function clearResults() {
      suggestions = [];
      activeIndex = -1;
      results.innerHTML = '';
      setExpanded(false);
    }

    function setActiveIndex(index) {
      var buttons = results.querySelectorAll('.openastro-result-btn');
      if (!suggestions.length || !buttons.length) {
        activeIndex = -1;
        input.removeAttribute('aria-activedescendant');
        return;
      }

      activeIndex = (index + suggestions.length) % suggestions.length;
      Array.prototype.forEach.call(buttons, function (button, idx) {
        var isActive = idx === activeIndex;
        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-selected', isActive ? 'true' : 'false');
        if (isActive) {
          input.setAttribute('aria-activedescendant', button.id);
          button.scrollIntoView({ block: 'nearest' });
        }
      });
    }

    function choose(item) {
      requestId += 1;
      input.value = cityValueLabel(item);
      setMessage('Selected ' + cityValueLabel(item) + (cityTimezoneLabel(item) ? ' - ' + cityTimezoneLabel(item) : '') + '.');
      clearResults();
      onSelect(item);
    }

    function renderSuggestions(items, query) {
      results.innerHTML = '';
      suggestions = items;
      activeIndex = -1;

      items.forEach(function (item, idx) {
        var button = create('button', {
          id: resultsId + '-option-' + idx,
          type: 'button',
          class: 'openastro-result-btn',
          role: 'option',
          'aria-selected': 'false',
          'aria-label': citySuggestionLabel(item)
        });
        var main = create('span', { class: 'openastro-result-main' });
        var primary = create('span', { class: 'openastro-result-primary' });
        var countryCode = cityCountryCode(item);
        primary.appendChild(create('span', { class: 'openastro-result-name' }, item.name || 'Unknown city'));
        if (countryCode) {
          primary.appendChild(create('span', { class: 'openastro-result-country' }, countryCode));
        }
        main.appendChild(primary);
        button.appendChild(main);

        var metaParts = cityMetaParts(item);
        if (metaParts.length) {
          var meta = create('span', { class: 'openastro-result-meta' });
          metaParts.forEach(function (part) {
            meta.appendChild(create('span', { class: 'openastro-result-meta-item' }, part));
          });
          button.appendChild(meta);
        }

        button.addEventListener('click', function () {
          choose(item);
        });
        results.appendChild(button);
      });

      setExpanded(true);
      setActiveIndex(0);
      setMessage(items.length + ' match' + (items.length === 1 ? '' : 'es') + ' for "' + query + '". Use arrow keys or click a city.');
    }

    input.addEventListener('input', function () {
      var parsedSearch = parseCitySearchInput(input.value);
      onSelect(null);
      clearResults();
      setMessage('');
      if (timer) {
        clearTimeout(timer);
      }
      requestId += 1;
      var currentRequestId = requestId;
      if (parsedSearch.query.length < 2) {
        if (parsedSearch.query.length > 0) {
          setMessage('Type at least 2 characters to search cities.');
        }
        return;
      }

      setMessage('Searching cities...');
      timer = setTimeout(function () {
        var path = '/geo/search?q=' + encodeURIComponent(parsedSearch.query) + '&limit=8';
        if (parsedSearch.country) {
          path += '&country=' + encodeURIComponent(parsedSearch.country);
        }

        api(path, { method: 'GET' })
          .then(function (result) {
            if (currentRequestId !== requestId) {
              return;
            }
            clearResults();
            setMessage('');
            if (!result.ok || !result.body || !Array.isArray(result.body.results)) {
              setMessage('Could not load city suggestions.');
              return;
            }
            if (result.body.results.length === 0) {
              setMessage('No cities found. Try another query.');
              return;
            }
            renderSuggestions(sortCitySuggestions(result.body.results, parsedSearch.query), parsedSearch.query);
          })
          .catch(function () {
            if (currentRequestId !== requestId) {
              return;
            }
            clearResults();
            setMessage('Could not load city suggestions.');
          });
      }, 300);
    });

    input.addEventListener('keydown', function (event) {
      if (!suggestions.length) {
        return;
      }
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        setActiveIndex(activeIndex < 0 ? 0 : activeIndex + 1);
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        setActiveIndex(activeIndex < 0 ? suggestions.length - 1 : activeIndex - 1);
      } else if (event.key === 'Enter' && activeIndex >= 0) {
        event.preventDefault();
        choose(suggestions[activeIndex]);
      } else if (event.key === 'Escape') {
        event.preventDefault();
        clearResults();
        setMessage('');
      }
    });

    input.addEventListener('blur', function () {
      window.setTimeout(function () {
        if (!results.contains(document.activeElement)) {
          clearResults();
        }
      }, 120);
    });

    return {
      clear: function () {
        requestId += 1;
        input.value = '';
        setMessage('');
        clearResults();
        onSelect(null);
      }
    };
  }

  function isEphemerisMode(mode) {
    return mode === 'ephemeris';
  }

  function isDailyHoroscopeMode(mode) {
    return mode === 'daily-horoscope';
  }

  function isPersonalizedHoroscopeMode(mode) {
    return mode === 'personalized-horoscope';
  }

  function isAstrologyBotMode(mode) {
    return mode === 'astrology-bot';
  }

  function isVedicMode(mode) {
    return mode === 'vedic-chart' || mode === 'vedic-workbench';
  }

  function defaultTimezone() {
    try {
      return Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
    } catch (e) {
      return 'UTC';
    }
  }

  function titleForSign(sign) {
    var normalized = String(sign || '').toLowerCase();
    for (var i = 0; i < ZODIAC_SIGNS.length; i += 1) {
      if (ZODIAC_SIGNS[i].id === normalized) {
        return ZODIAC_SIGNS[i].label;
      }
    }
    return titleCase(normalized || 'Sign');
  }

  function dateTodayValue() {
    var now = new Date();
    return [now.getFullYear(), pad2(now.getMonth() + 1), pad2(now.getDate())].join('-');
  }

  function horoscopeArray(items) {
    return Array.isArray(items) ? items.filter(Boolean) : [];
  }

  function horoscopeLabel(value) {
    if (!value) {
      return '';
    }
    if (typeof value === 'string') {
      return value;
    }
    if (typeof value === 'object') {
      return value.label || value.display || value.name || value.value || value.key || '';
    }
    return String(value);
  }

  function horoscopeFactorReason(reason) {
    if (!reason) {
      return '';
    }
    if (typeof reason === 'string') {
      return reason;
    }
    if (typeof reason === 'object') {
      return [reason.main, reason.secondary].filter(Boolean).join(' ');
    }
    return String(reason);
  }

  function horoscopeDateTimeLabel(value) {
    if (!value) {
      return '';
    }
    var parsed = new Date(value);
    if (!Number.isNaN(parsed.getTime())) {
      return parsed.toLocaleString(undefined, {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }
    return String(value);
  }

  function personalizedHoroscopeContextHtml(personal, lang) {
    if (!personal || typeof personal !== 'object') {
      return '';
    }
    var transits = horoscopeArray(personal.transits_top);
    var focusAreas = horoscopeArray(personal.focus_areas);
    var context = personal.day_context && typeof personal.day_context === 'object' ? personal.day_context : {};
    var exactHits = horoscopeArray(context.exact_hits);
    var activeWindows = horoscopeArray(context.active_windows);
    var dominantTopics = horoscopeArray(context.dominant_topics);
    var nextShift = context.next_shift && typeof context.next_shift === 'object' ? context.next_shift : null;
    var confidence = personal.confidence_score;

    if (!transits.length && !focusAreas.length && !exactHits.length && !activeWindows.length && !dominantTopics.length && !nextShift && !confidence) {
      return '';
    }

    var html = '<div class="' + expandClassNames('openastro-horoscope-personal') + '">';
    html += '<h4>' + esc(t('Personal timing', lang)) + '</h4>';
    if (focusAreas.length || confidence) {
      html += '<div class="' + expandClassNames('openastro-horoscope-personal-meta') + '">';
      focusAreas.forEach(function (item) {
        html += '<span>' + esc(horoscopeLabel(item)) + '</span>';
      });
      if (confidence) {
        html += '<span>' + esc(t('Confidence', lang)) + ': ' + esc(confidence) + '</span>';
      }
      html += '</div>';
    }

    if (transits.length) {
      html += '<section><h5>' + esc(t('Top personal transits', lang)) + '</h5><div class="' + expandClassNames('openastro-horoscope-transits') + '">';
      transits.slice(0, 5).forEach(function (item) {
        if (!item || typeof item !== 'object') {
          return;
        }
        var explanation = item.explanation && typeof item.explanation === 'object' ? item.explanation : {};
        var supporting = horoscopeArray(explanation.supporting);
        var tags = horoscopeArray(explanation.tags);
        html += '<article>';
        html += '<h6>' + esc(item.label || t('Transit', lang)) + '</h6>';
        var meta = [];
        if (item.score !== undefined && item.score !== null) meta.push(t('Score', lang) + ' ' + item.score);
        if (item.exact_at) meta.push(t('Exact', lang) + ' ' + horoscopeDateTimeLabel(item.exact_at));
        if (meta.length) html += '<p class="' + expandClassNames('openastro-horoscope-transit-meta') + '">' + esc(meta.join(' · ')) + '</p>';
        if (explanation.main) html += '<p>' + esc(explanation.main) + '</p>';
        if (supporting.length) {
          html += '<ul>' + supporting.map(function (support) { return '<li>' + esc(horoscopeLabel(support)) + '</li>'; }).join('') + '</ul>';
        }
        if (tags.length) {
          html += '<div class="' + expandClassNames('openastro-horoscope-keywords') + '">' + tags.slice(0, 6).map(function (tag) { return '<span>' + esc(horoscopeLabel(tag)) + '</span>'; }).join('') + '</div>';
        }
        html += '</article>';
      });
      html += '</div></section>';
    }

    if (nextShift || exactHits.length || activeWindows.length || dominantTopics.length) {
      html += '<section class="' + expandClassNames('openastro-horoscope-timing') + '"><h5>' + esc(t('Timing context', lang)) + '</h5>';
      if (nextShift) {
        html += '<p><strong>' + esc(t('Next shift', lang)) + ':</strong> ' + esc([nextShift.label, horoscopeDateTimeLabel(nextShift.timestamp)].filter(Boolean).join(' · ')) + '</p>';
      }
      if (exactHits.length) {
        html += '<div><h6>' + esc(t('Exact hits', lang)) + '</h6><ul>' + exactHits.slice(0, 5).map(function (hit) {
          return '<li>' + esc([horoscopeLabel(hit), horoscopeDateTimeLabel(hit && hit.timestamp)].filter(Boolean).join(' · ')) + '</li>';
        }).join('') + '</ul></div>';
      }
      if (activeWindows.length) {
        html += '<div><h6>' + esc(t('Active windows', lang)) + '</h6><ul>' + activeWindows.slice(0, 4).map(function (windowItem) {
          return '<li>' + esc([horoscopeLabel(windowItem), horoscopeDateTimeLabel(windowItem && windowItem.start), horoscopeDateTimeLabel(windowItem && windowItem.end)].filter(Boolean).join(' · ')) + '</li>';
        }).join('') + '</ul></div>';
      }
      if (dominantTopics.length) {
        html += '<div><h6>' + esc(t('Dominant topics', lang)) + '</h6><ul>' + dominantTopics.slice(0, 4).map(function (topic) {
          return '<li>' + esc([topic && topic.title, topic && topic.category].filter(Boolean).join(' · ')) + '</li>';
        }).join('') + '</ul></div>';
      }
      html += '</section>';
    }

    html += '</div>';
    return html;
  }

  function dailyHoroscopeHtml(response, lang, cfg) {
    var data = response && response.data ? response.data : (response || {});
    var horoscopeCfg = cfg && cfg.horoscope ? cfg.horoscope : {};
    var content = data.content && typeof data.content === 'object' ? data.content : {};
    var forecast = data.forecast && typeof data.forecast === 'object' ? data.forecast : {};
    var lucky = data.lucky && typeof data.lucky === 'object' ? data.lucky : {};
    var astro = data.astro && typeof data.astro === 'object' ? data.astro : {};
    var personal = data.personal && typeof data.personal === 'object' ? data.personal : null;
    var sign = data.sign || '';
    var signLabel = titleForSign(sign);
    var dateLabel = data.date || (response && response.meta && response.meta.settings && response.meta.settings.date_resolved) || '';
    var title = signLabel + (dateLabel ? ' - ' + dateLabel : '');
    var summary = content.text || content.summary || content.body || content.overview || forecast.summary || forecast.text || forecast.body || forecast.overview || data.summary || data.text || '';
    var theme = horoscopeLabel(content.theme || forecast.theme || data.theme);
    var keywords = horoscopeArray(content.keywords || forecast.keywords || data.keywords);
    var scores = data.scores && typeof data.scores === 'object' ? data.scores : {};
    var scoreFactors = horoscopeArray(data.score_factors);
    var highlights = horoscopeArray(astro.highlights);
    var doItems = horoscopeArray(content.do).concat(horoscopeArray(forecast.do));
    var dontItems = horoscopeArray(content.dont).concat(horoscopeArray(forecast.dont));
    var insights = horoscopeArray(content.supporting_insights).concat(horoscopeArray(forecast.supporting_insights));
    var luckyTime = lucky.time || lucky.time_window;

    var html = '<section class="' + expandClassNames('openastro-card openastro-horoscope-card') + '">';
    html += '<span class="' + expandClassNames('openastro-ephemeris-eyebrow openastro-horoscope-eyebrow') + '">' + esc(t(personal ? 'Personalized Horoscope' : 'Daily Horoscope', lang)) + '</span>';
    html += '<h3>' + glyphSpan(glyphForSign(sign)) + esc(title) + '</h3>';
    if (horoscopeCfg.showTheme !== false && (theme || keywords.length)) {
      html += '<div class="' + expandClassNames('openastro-horoscope-theme-row') + '">';
      if (theme) {
        html += '<span class="' + expandClassNames('openastro-horoscope-theme') + '">' + esc(theme) + '</span>';
      }
      if (keywords.length) {
        html += '<div class="' + expandClassNames('openastro-horoscope-keywords') + '">' + keywords.map(function (item) {
          return '<span>' + esc(horoscopeLabel(item)) + '</span>';
        }).join('') + '</div>';
      }
      html += '</div>';
    }
    if (horoscopeCfg.showSummary !== false && summary) {
      html += '<p class="' + expandClassNames('openastro-horoscope-summary') + '">' + esc(summary) + '</p>';
    }

    if (horoscopeCfg.showScores !== false && Object.keys(scores).length) {
      html += '<div class="' + expandClassNames('openastro-horoscope-scores') + '">';
      ['overall', 'love', 'career', 'money', 'health'].forEach(function (key) {
        if (scores[key] === undefined || scores[key] === null || scores[key] === '') {
          return;
        }
        html += '<span><strong>' + esc(titleCase(key)) + '</strong>' + esc(scores[key]) + '</span>';
      });
      html += '</div>';
    }

    if (horoscopeCfg.showScoreFactors !== false && scoreFactors.length) {
      html += '<details class="' + expandClassNames('openastro-horoscope-score-factors') + '">';
      html += '<summary>' + esc(t('Why these scores?', lang)) + '</summary>';
      html += '<div>';
      scoreFactors.forEach(function (factor) {
        if (!factor || typeof factor !== 'object') {
          return;
        }
        var dimension = factor.dimension ? titleCase(factor.dimension) : '';
        var reason = horoscopeFactorReason(factor.reason);
        var drivers = horoscopeArray(factor.drivers).map(function (driver) {
          return horoscopeLabel(driver);
        }).filter(Boolean);
        if (!dimension && !reason && !drivers.length) {
          return;
        }
        html += '<section>';
        if (dimension) {
          html += '<h4>' + esc(dimension) + '</h4>';
        }
        if (reason) {
          html += '<p>' + esc(reason) + '</p>';
        }
        if (drivers.length) {
          html += '<small>' + esc(drivers.join(' · ')) + '</small>';
        }
        html += '</section>';
      });
      html += '</div></details>';
    }

    if (horoscopeCfg.showPersonalContext !== false) {
      html += personalizedHoroscopeContextHtml(personal, lang);
    }

    if (horoscopeCfg.showLucky !== false && (lucky.color || lucky.number || luckyTime || lucky.direction)) {
      html += '<dl class="' + expandClassNames('openastro-horoscope-lucky') + '">';
      [['Color', lucky.color], ['Number', lucky.number], ['Time', luckyTime], ['Direction', lucky.direction]].forEach(function (row) {
        var label = horoscopeLabel(row[1]);
        if (label) {
          html += '<div><dt>' + esc(row[0]) + '</dt><dd>' + esc(label) + '</dd></div>';
        }
      });
      html += '</dl>';
    }

    if (horoscopeCfg.showGuidance !== false && (doItems.length || dontItems.length || insights.length)) {
      html += '<div class="' + expandClassNames('openastro-horoscope-guidance') + '">';
      if (doItems.length) {
        html += '<section><h4>' + esc(t('Do', lang)) + '</h4><ul>' + doItems.map(function (item) { return '<li>' + esc(horoscopeLabel(item)) + '</li>'; }).join('') + '</ul></section>';
      }
      if (dontItems.length) {
        html += '<section><h4>' + esc(t('Avoid', lang)) + '</h4><ul>' + dontItems.map(function (item) { return '<li>' + esc(horoscopeLabel(item)) + '</li>'; }).join('') + '</ul></section>';
      }
      if (insights.length) {
        html += '<section><h4>' + esc(t('Supporting Insights', lang)) + '</h4><ul>' + insights.map(function (item) { return '<li>' + esc(horoscopeLabel(item)) + '</li>'; }).join('') + '</ul></section>';
      }
      html += '</div>';
    }

    if (horoscopeCfg.showAstro !== false && (astro.moon_sign || astro.moon_phase || highlights.length)) {
      html += '<div class="' + expandClassNames('openastro-horoscope-astro') + '">';
      if (astro.moon_sign) {
        html += '<span>' + esc(t('Moon Sign', lang)) + ': ' + esc(horoscopeLabel(astro.moon_sign)) + '</span>';
      }
      if (astro.moon_phase) {
        html += '<span>' + esc(t('Moon Phase', lang)) + ': ' + esc(horoscopeLabel(astro.moon_phase)) + '</span>';
      }
      highlights.forEach(function (item) {
        html += '<span>' + esc(horoscopeLabel(item)) + '</span>';
      });
      html += '</div>';
    }

    html += '</section>';
    return html;
  }

  function mountDailyHoroscope(container, cfg, lang) {
    var form = create('form', { class: 'openastro-form openastro-horoscope-form' });
    var sign = create('select', { class: 'openastro-input', 'aria-label': t('Zodiac Sign', lang) });
    ZODIAC_SIGNS.forEach(function (item) {
      sign.appendChild(create('option', { value: item.id }, item.label));
    });
    var date = create('input', { type: 'date', class: 'openastro-input', 'aria-label': t('Date', lang) });
    var timezone = create('input', { type: 'text', class: 'openastro-input', placeholder: 'Europe/Paris', 'aria-label': t('Timezone', lang) });
    var submit = create('button', { type: 'submit', class: 'openastro-btn' }, t('Generate', lang));
    var status = create('p', { class: 'openastro-status', 'aria-live': 'polite' }, '');
    var output = create('div', { class: 'openastro-output' });

    date.value = dateTodayValue();
    timezone.value = defaultTimezone();
    form.appendChild(sign);
    form.appendChild(date);
    form.appendChild(timezone);
    form.appendChild(submit);
    form.appendChild(status);
    container.appendChild(form);
    container.appendChild(output);

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var payload = {
        sign: sign.value,
        date: date.value || 'today',
        tz_str: timezone.value || defaultTimezone()
      };
      submit.disabled = true;
      status.textContent = t('Generating...', lang);
      output.innerHTML = '';
      api('/western/daily-horoscope', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (result) {
          if (!result.ok || !result.body) {
            status.textContent = (result.body && result.body.message) || t('Could not generate daily horoscope.', lang);
            return;
          }
          output.innerHTML = dailyHoroscopeHtml(result.body, lang, cfg);
          status.textContent = t('Done.', lang);
          window.dispatchEvent(new CustomEvent('openastro:daily-horoscope:generated', {
            detail: {
              request: payload,
              response: result.body
            }
          }));
        })
        .catch(function () {
          status.textContent = t('Unexpected error while generating data.', lang);
        })
        .finally(function () {
          submit.disabled = false;
        });
    });
  }

  function ephemerisBodyById(id) {
    for (var i = 0; i < EPHEMERIS_BODIES.length; i += 1) {
      if (EPHEMERIS_BODIES[i].id === id) {
        return EPHEMERIS_BODIES[i];
      }
    }
    return null;
  }

  function normalizeEphemerisColumn(value) {
    return String(value || '').toLowerCase().replace(/[\s-]+/g, '_');
  }

  function ephemerisBodyByApi(api) {
    var normalized = normalizeEphemerisColumn(api);
    if (normalized === 'north_node') {
      normalized = 'true_node';
    }
    for (var i = 0; i < EPHEMERIS_BODIES.length; i += 1) {
      if (normalizeEphemerisColumn(EPHEMERIS_BODIES[i].api) === normalized) {
        return EPHEMERIS_BODIES[i];
      }
    }
    return null;
  }

  function ephemerisMonthTitle(year, month) {
    return (EPHEMERIS_MONTHS[month - 1] || 'Month') + ' ' + year;
  }

  function ephemerisTodayIso() {
    var now = new Date();
    return [now.getFullYear(), pad2(now.getMonth() + 1), pad2(now.getDate())].join('-');
  }

  function ephemerisColumnLabel(column) {
    if (column === 'date') return 'Date';
    if (column === 'weekday') return 'Day';
    if (column === 'sidereal_time') return 'GST';
    var body = ephemerisBodyByApi(column);
    return body ? (body.tableLabel || body.label) : column;
  }

  function ephemerisHeaderHtml(column) {
    var body = ephemerisBodyByApi(column);
    if (!body) {
      return '<span class="' + expandClassNames('openastro-ephemeris-head-text') + '">' + esc(ephemerisColumnLabel(column)) + '</span>';
    }

    return '<span class="' + expandClassNames('openastro-ephemeris-head-body') + '">' +
      '<span class="' + expandClassNames('openastro-ephemeris-glyph') + '">' + esc(body.glyph) + '</span>' +
      '<span class="' + expandClassNames('openastro-ephemeris-head-label') + '">' + esc((body.tableLabel || body.label).toUpperCase()) + '</span>' +
      '</span>';
  }

  function ephemerisCellText(row, column) {
    if (column === 'date') {
      if (row.date && typeof row.date === 'object') {
        return row.date.day || row.date.iso || '';
      }
      return row.date || '';
    }
    if (column === 'weekday') {
      return row.weekday || '';
    }
    if (column === 'sidereal_time') {
      return row.sidereal_time || row.gst || '';
    }

    var cell = row[column];
    if (cell && typeof cell === 'object') {
      return cell.text || cell.position_text || cell.degree_text || '';
    }
    return cell || '';
  }

  function ephemerisCellClasses(row, column) {
    var classes = ['openastro-ephemeris-cell'];
    if (column === 'date') classes.push('openastro-ephemeris-date-cell');
    if (column === 'weekday') classes.push('openastro-ephemeris-day-cell');
    if (column === 'sidereal_time') classes.push('openastro-ephemeris-gst-cell');
    var cell = row[column];
    if (cell && typeof cell === 'object') {
      if (cell.motion_marker) classes.push('is-retrograde');
      if (cell.entered_sign) classes.push('is-ingress');
      if (cell.station) classes.push('is-station');
    }
    return expandClassNames(classes.join(' '));
  }

  function ephemerisTableHtml(data) {
    var rows = Array.isArray(data && data.rows) ? data.rows : [];
    var columns = data && data.display && Array.isArray(data.display.columns)
      ? data.display.columns
      : ['date', 'weekday', 'sidereal_time'];
    var today = ephemerisTodayIso();
    var html = '<table class="' + expandClassNames('openastro-ephemeris-table') + '"><thead><tr>';

    columns.forEach(function (column) {
      var colClass = 'openastro-ephemeris-col-' + normalizeEphemerisColumn(column);
      html += '<th class="' + expandClassNames(colClass) + '" scope="col">' + ephemerisHeaderHtml(column) + '</th>';
    });
    html += '</tr></thead><tbody>';

    if (!rows.length) {
      html += '<tr><td colspan="' + esc(columns.length) + '" class="' + expandClassNames('openastro-ephemeris-empty-cell') + '">No ephemeris rows returned.</td></tr>';
    } else {
      rows.forEach(function (row) {
        var rowIso = row.date && typeof row.date === 'object' ? row.date.iso : '';
        var rowClass = rowIso === today ? 'openastro-ephemeris-row is-today' : 'openastro-ephemeris-row';
        html += '<tr class="' + expandClassNames(rowClass) + '">';
        columns.forEach(function (column) {
          html += '<td class="' + ephemerisCellClasses(row, column) + '">' + esc(ephemerisCellText(row, column) || '-') + '</td>';
        });
        html += '</tr>';
      });
    }

    html += '</tbody></table>';
    return html;
  }

  function ephemerisCsvValue(value) {
    var text = String(value || '');
    if (/[",\n]/.test(text)) {
      return '"' + text.replace(/"/g, '""') + '"';
    }
    return text;
  }

  function ephemerisCsv(data) {
    var rows = Array.isArray(data && data.rows) ? data.rows : [];
    var columns = data && data.display && Array.isArray(data.display.columns)
      ? data.display.columns
      : ['date', 'weekday', 'sidereal_time'];
    var lines = [];
    lines.push(columns.map(function (column) {
      return ephemerisCsvValue(ephemerisColumnLabel(column));
    }).join(','));
    rows.forEach(function (row) {
      lines.push(columns.map(function (column) {
        return ephemerisCsvValue(ephemerisCellText(row, column));
      }).join(','));
    });
    return lines.join('\n');
  }

  function downloadEphemerisCsv(data, filename) {
    var blob = new Blob([ephemerisCsv(data)], { type: 'text/csv;charset=utf-8' });
    var url = window.URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
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

  function interpretationCategoryLabel(category) {
    var labels = {
      core_self: 'Core Self',
      mind: 'Mind',
      love_relating: 'Love and Relating',
      work_path: 'Work and Path',
      social_collective: 'Social and Collective',
      karmic_healing: 'Karmic and Healing'
    };
    var normalized = normalizeBodyToken(category);
    return labels[normalized] || titleCase(normalized || category || 'Theme');
  }

  function collectInterpretationSections(interpretation) {
    if (!interpretation || typeof interpretation !== 'object' || Array.isArray(interpretation)) {
      return [];
    }

    var sections = interpretation.sections;
    if (!sections || typeof sections !== 'object' || Array.isArray(sections)) {
      return [];
    }

    return Object.keys(sections).map(function (category) {
      var entries = sections[category];
      if (!Array.isArray(entries)) {
        return null;
      }
      var cleanEntries = entries.map(function (entry) {
        var title = '';
        var body = '';
        if (entry && typeof entry === 'object') {
          title = typeof entry.title === 'string' ? entry.title.trim() : '';
          body = textFromEntry(entry);
        } else if (typeof entry === 'string') {
          body = entry.trim();
        }
        if (!title || !body) {
          return null;
        }
        return { title: title, body: body };
      }).filter(Boolean);
      if (!cleanEntries.length) {
        return null;
      }
      return {
        category: category,
        title: interpretationCategoryLabel(category),
        entries: cleanEntries
      };
    }).filter(Boolean);
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

  function birthContextHtml(request, cityItem, lang) {
    if (!request) {
      return '';
    }

    var dateLabel = [request.year, pad2(request.month), pad2(request.day)].join('-');
    var timeLabel = request.time_known === false ? t('Time unknown', lang) : pad2(request.hour) + ':' + pad2(request.minute);
    var placeLabel = cityItem ? cityValueLabel(cityItem) : (request.city || '');
    var coords = Number.isFinite(Number(request.lat)) && Number.isFinite(Number(request.lng))
      ? Number(request.lat).toFixed(4) + ', ' + Number(request.lng).toFixed(4)
      : '';
    var rows = [
      [t('Date', lang), dateLabel],
      [t('Time', lang), timeLabel],
      [t('Place', lang), placeLabel || t('Unknown city', lang)],
      [t('Timezone', lang), request.tz_str || '-'],
      [t('Coordinates', lang), coords || '-']
    ];

    var html = '<section class="' + expandClassNames('openastro-card openastro-context-card') + '"><h3>' + esc(t('Birth Context', lang)) + '</h3>';
    html += '<dl class="' + expandClassNames('openastro-context-list') + '">';
    rows.forEach(function (row) {
      html += '<div><dt>' + esc(row[0]) + '</dt><dd>' + esc(row[1]) + '</dd></div>';
    });
    html += '</dl></section>';
    return html;
  }

  function normalizeBodyToken(value) {
    return String(value || '').toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
  }

  function titleCase(value) {
    return String(value || '').replace(/_/g, ' ').replace(/\b\w/g, function (char) {
      return char.toUpperCase();
    });
  }

  function aspectTypeLabel(type, lang) {
    var normalized = normalizeBodyToken(type);
    var fallback = titleCase(type || 'aspect');
    var key = 'aspect_type_' + normalized;
    var translated = t(key, lang);
    return translated === key ? fallback : translated;
  }

  function planetAspectAliases(planet) {
    var aliases = [
      normalizeBodyToken(planet.id),
      normalizeBodyToken(planet.name)
    ].filter(Boolean);
    if (aliases.indexOf('north_node') >= 0) {
      aliases.push('true_node', 'mean_node');
    }
    if (aliases.indexOf('true_node') >= 0 || aliases.indexOf('mean_node') >= 0) {
      aliases.push('north_node');
    }
    if (aliases.indexOf('ascendant') >= 0) {
      aliases.push('asc');
    }
    if (aliases.indexOf('asc') >= 0) {
      aliases.push('ascendant');
    }
    if (aliases.indexOf('midheaven') >= 0) {
      aliases.push('mc');
    }
    if (aliases.indexOf('mc') >= 0) {
      aliases.push('midheaven');
    }
    return aliases.filter(function (alias, idx) {
      return alias && aliases.indexOf(alias) === idx;
    });
  }

  function aspectMatchesPlanet(aspect, aliases) {
    var p1 = normalizeBodyToken(aspect.p1);
    var p2 = normalizeBodyToken(aspect.p2);
    return aliases.indexOf(p1) >= 0 || aliases.indexOf(p2) >= 0;
  }

  function otherAspectBody(aspect, aliases, focusName) {
    var p1 = normalizeBodyToken(aspect.p1);
    var p2 = normalizeBodyToken(aspect.p2);
    if (aliases.indexOf(p1) >= 0) {
      return aspect.p2 || focusName;
    }
    if (aliases.indexOf(p2) >= 0) {
      return aspect.p1 || focusName;
    }
    return aspect.p2 || aspect.p1 || focusName;
  }

  function aspectOrbLabel(aspect) {
    if (typeof aspect.orb === 'number') {
      return aspect.orb.toFixed(2) + '°';
    }
    return aspect.orb ? String(aspect.orb) : '-';
  }

  function aspectBodyAliases(value) {
    var normalized = normalizeBodyToken(value);
    var aliases = [normalized];
    if (normalized === 'asc') {
      aliases.push('ascendant');
    }
    if (normalized === 'ascendant') {
      aliases.push('asc');
    }
    if (normalized === 'mc') {
      aliases.push('midheaven');
    }
    if (normalized === 'midheaven') {
      aliases.push('mc');
    }
    if (normalized === 'north_node') {
      aliases.push('true_node', 'mean_node');
    }
    if (normalized === 'true_node' || normalized === 'mean_node') {
      aliases.push('north_node');
    }
    return aliases.filter(function (alias, idx) {
      return alias && aliases.indexOf(alias) === idx;
    });
  }

  function aspectKeyCandidates(aspect) {
    var p1Aliases = aspectBodyAliases(aspect.p1);
    var p2Aliases = aspectBodyAliases(aspect.p2);
    var type = normalizeBodyToken(aspect.type);
    var keys = [];
    p1Aliases.forEach(function (p1) {
      p2Aliases.forEach(function (p2) {
        keys.push('aspect.' + p1 + '.' + type + '.' + p2);
        keys.push('aspect.' + p2 + '.' + type + '.' + p1);
      });
    });
    return keys.filter(function (key, idx) {
      return key && keys.indexOf(key) === idx;
    });
  }

  function findAspectAtom(atoms, aspect) {
    var candidates = aspectKeyCandidates(aspect);
    for (var i = 0; i < atoms.length; i += 1) {
      var key = String(atoms[i].key || '').toLowerCase();
      if (candidates.indexOf(key) >= 0) {
        return atoms[i];
      }
    }
    return null;
  }

  function aspectSymbol(type) {
    var normalized = normalizeBodyToken(type);
    var symbols = {
      conjunction: '☌',
      opposition: '☍',
      trine: '△',
      square: '□',
      sextile: '⚹',
      quincunx: '⚻',
      inconjunct: '⚻',
      semi_square: '∠',
      semisquare: '∠',
      sesquiquadrate: '⚼',
      sesqui_square: '⚼'
    };
    return symbols[normalized] || '•';
  }

  function aspectMotionLabel(aspect, lang) {
    if (typeof aspect.is_applying === 'boolean') {
      return aspect.is_applying ? t('Applying', lang) : t('Separating', lang);
    }
    if (aspect.motion) {
      return titleCase(aspect.motion);
    }
    return t('Unknown', lang);
  }

  function aspectStrengthLabel(aspect) {
    var parts = [];
    var label = aspect.strength_label || aspect.strengthLabel || aspect.intensity || '';
    var score = aspect.strength_score || aspect.strengthScore || aspect.score || aspect.power;
    if (label) {
      parts.push(titleCase(label));
    } else if (typeof aspect.is_major === 'boolean') {
      parts.push(aspect.is_major ? 'Major' : 'Minor');
    }
    if (typeof score === 'number') {
      parts.push(Math.round(score) + '/100');
    } else if (typeof score === 'string' && score) {
      parts.push(score);
    }
    return parts.join(', ');
  }

  function aspectMetaLabel(aspect, lang) {
    var parts = [aspectOrbLabel(aspect), aspectMotionLabel(aspect, lang)];
    var strength = aspectStrengthLabel(aspect);
    if (strength) {
      parts.push(strength);
    }
    return '(' + parts.join(', ') + ')';
  }

  function aspectModalMetaLabel(aspect, lang) {
    var degree = typeof aspect.deg === 'number' ? aspect.deg + '°' : (aspect.degree || aspect.angle || '-');
    return 'Orb ' + aspectOrbLabel(aspect) + ' • ' + degree + ' • ' + aspectMotionLabel(aspect, lang);
  }

  function aspectBodyName(value) {
    var normalized = normalizeBodyToken(value);
    var labels = {
      asc: 'Asc',
      ascendant: 'Ascendant',
      dsc: 'Dsc',
      descendant: 'Descendant',
      mc: 'Mc',
      midheaven: 'Midheaven',
      ic: 'Ic',
      north_node: 'North Node',
      true_node: 'North Node',
      mean_node: 'North Node'
    };
    return labels[normalized] || titleCase(value || normalized || '-');
  }

  function aspectListTitle(aspect, aliases, lang) {
    return aspectTypeLabel(aspect.type, lang) + ' ' + aspectBodyName(otherAspectBody(aspect, aliases, '-'));
  }

  function aspectModalTitle(aspect, lang) {
    return aspectTypeLabel(aspect.type, lang) + ' ' + aspectBodyName(aspect.p1) + ' - ' + aspectBodyName(aspect.p2);
  }

  function aspectBodyCellHtml(value, showGlyphs) {
    return '<span class="' + expandClassNames('openastro-aspect-table-cell') + '">' +
      (showGlyphs ? bodyGlyphHtml(value) : '') +
      '<span>' + esc(aspectBodyName(value)) + '</span>' +
      '</span>';
  }

  function aspectTypeCellHtml(aspect, lang, showGlyphs) {
    return '<span class="' + expandClassNames('openastro-aspect-table-cell') + '">' +
      (showGlyphs ? '<span class="' + expandClassNames('openastro-aspect-table-symbol') + '" aria-hidden="true">' + esc(aspectSymbol(aspect.type)) + '</span>' : '') +
      '<span>' + esc(aspectTypeLabel(aspect.type, lang) || '-') + '</span>' +
      '</span>';
  }

  function planetHouseLabel(planet) {
    var value = planet && (planet.house || planet.house_number || planet.houseNumber || planet.house_id || planet.houseId);
    var label = String(value || '').trim();
    return label && label !== '-' && label !== '0' ? label : '';
  }

  function planetAspectListHtml(planet, aspects, atoms, lang, modalStore, showGlyphs) {
    var aliases = planetAspectAliases(planet);
    if (!aliases.length) {
      return '';
    }

    var matches = aspects.filter(function (aspect) {
      return aspectMatchesPlanet(aspect, aliases);
    });

    if (!matches.length) {
      return '';
    }

    var html = '<div class="' + expandClassNames('openastro-planet-aspects') + '"><h5>' + esc(fmt('{planet} aspects', { planet: planet.name || planet.id || '-' }, lang)) + '</h5><ul class="' + expandClassNames('openastro-aspect-list') + '">';
    matches.forEach(function (aspect) {
      var atom = findAspectAtom(atoms, aspect);
      var idx = modalStore.length;
      var title = aspectModalTitle(aspect, lang);
      modalStore.push({
        title: title,
        heading: atom && atom.title ? atom.title : title,
        body: atom && atom.body ? atom.body : t('Interpretation was not returned for this aspect.', lang),
        meta: aspectModalMetaLabel(aspect, lang)
      });
      html += '<li class="' + expandClassNames('openastro-aspect-item') + '">';
      if (showGlyphs) {
        html += '<span class="' + expandClassNames('openastro-aspect-symbol') + '" aria-hidden="true">' + esc(aspectSymbol(aspect.type)) + '</span>';
      }
      html += '<button type="button" class="' + expandClassNames('openastro-aspect-link') + '" data-openastro-aspect-modal="' + esc(idx) + '">' + esc(aspectListTitle(aspect, aliases, lang)) + '</button>';
      html += '<span class="' + expandClassNames('openastro-aspect-meta') + '">' + esc(aspectMetaLabel(aspect, lang)) + '</span>';
      html += '</li>';
    });
    html += '</ul></div>';
    return html;
  }

  function thematicInterpretationHtml(interpretation, reportCfg, lang) {
    var sections = collectInterpretationSections(interpretation);
    if (!sections.length) {
      return '';
    }

    var html = '<section class="' + expandClassNames('openastro-card openastro-thematic-interpretation') + '"><h3>' + esc(t('Thematic Interpretation', lang)) + '</h3>';
    sections.forEach(function (section) {
      html += '<div class="' + expandClassNames('openastro-theme-group') + '"><h4>' + esc(section.title) + '</h4>';
      section.entries.forEach(function (entry) {
        html += '<article class="' + expandClassNames('openastro-theme-entry') + '">';
        html += '<h5>' + esc(entry.title) + '</h5>';
        html += '<p>' + esc(cropText(entry.body, reportCfg.detailLevel)) + '</p>';
        html += '</article>';
      });
      html += '</div>';
    });
    html += '</section>';
    return html;
  }

  function reportHtml(response, svg, mode, reportCfg, requestPayload, cityItem, lang, modalStore) {
    var planets = Array.isArray(response.planets) ? response.planets : [];
    var aspects = Array.isArray(response.aspects) ? response.aspects.slice() : [];
    var atoms = collectInterpretationAtoms(response.interpretation);

    aspects.sort(function (a, b) {
      return Number(a.orb || 0) - Number(b.orb || 0);
    });

    var html = '<h2 class="' + expandClassNames('openastro-report-title') + '">' + esc(t('Natal Report', lang)) + '</h2>';

    if (mode === 'chart-report' && svg) {
      html += chartSectionHtml(svg, reportCfg, lang);
    }

    if (reportCfg.showBirthContext) {
      html += birthContextHtml(requestPayload, cityItem, lang);
    }

    if (reportCfg.showThematicInterpretation) {
      html += thematicInterpretationHtml(response.interpretation, reportCfg, lang);
    }

    if (reportCfg.showAngles && requestPayload && requestPayload.time_known !== false) {
      var ascAtom = findAngleAtom(atoms, 'ascendant') || findAngleAtom(atoms, 'asc');
      var mcAtom = findAngleAtom(atoms, 'midheaven') || findAngleAtom(atoms, 'mc');
      var asc = response.angles_details && response.angles_details.asc ? response.angles_details.asc : null;
      var mc = response.angles_details && response.angles_details.mc ? response.angles_details.mc : null;

      html += '<section class="' + expandClassNames('openastro-card') + '"><h3>' + esc(t('1st House / ASC', lang)) + '</h3>';
      if (asc) {
        var ascSignGlyph = glyphForSign(asc.sign);
        html += '<p><strong>' + esc(t('Sign:', lang)) + '</strong> ' + esc(asc.sign || '-') + (ascSignGlyph ? ' ' + glyphSpan(ascSignGlyph) : '') + ' <strong>' + esc(t('House:', lang)) + '</strong> ' + esc(asc.house || '-') + '</p>';
      }
      html += '<p>' + esc(cropText(ascAtom ? ascAtom.body : t('Ascendant interpretation was not returned for this chart.', lang), reportCfg.detailLevel)) + '</p>';
      html += '</section>';

      html += '<section class="' + expandClassNames('openastro-card') + '"><h3>' + esc(t('10th House Cusp / MC', lang)) + '</h3>';
      if (mc) {
        var mcSignGlyph = glyphForSign(mc.sign);
        html += '<p><strong>' + esc(t('Sign:', lang)) + '</strong> ' + esc(mc.sign || '-') + (mcSignGlyph ? ' ' + glyphSpan(mcSignGlyph) : '') + ' <strong>' + esc(t('House:', lang)) + '</strong> ' + esc(mc.house || '-') + '</p>';
      }
      html += '<p>' + esc(cropText(mcAtom ? mcAtom.body : t('Midheaven interpretation was not returned for this chart.', lang), reportCfg.detailLevel)) + '</p>';
      html += '</section>';
    }

    html += '<section class="' + expandClassNames('openastro-card') + '"><h3>' + esc(t('Planet Interpretations', lang)) + '</h3>';
    planets.slice(0, reportCfg.maxPlanets).forEach(function (planet) {
      var atom = findPlanetAtom(atoms, planet);
      var defaultBody = PLANET_OVERVIEW[String(planet.id || '').toLowerCase()] || t('Interpretation not available for this planet.', lang);
      var body = atom ? atom.body : defaultBody;
      var bodyGlyph = bodyGlyphHtml(planet.id);
      var signGlyph = glyphForSign(planet.sign);
      var planetName = planet.name || planet.id || '-';
      var signName = planet.sign || '-';
      var houseLabel = planetHouseLabel(planet);
      html += '<article class="' + expandClassNames('openastro-planet-item') + '">';
      html += '<h4>' + esc(planetName) + (bodyGlyph ? ' ' + bodyGlyph : '') + esc(' in ' + signName) + (signGlyph ? ' ' + glyphSpan(signGlyph) : '') + (houseLabel ? esc(' (House ' + houseLabel + ')') : '') + '</h4>';
      html += '<p>' + esc(cropText(body, reportCfg.detailLevel)) + '</p>';
      if (reportCfg.showAspectLinks) {
        html += planetAspectListHtml(planet, aspects, atoms, lang, modalStore || [], reportCfg.showAspectGlyphs);
      }
      html += '</article>';
    });
    html += '</section>';

    html += '<section class="' + expandClassNames('openastro-card') + '"><h3>' + esc(t('All Aspect Dynamics', lang)) + '</h3>';
    if (reportCfg.showTables) {
      html += '<table><thead><tr><th>' + esc(t('P1', lang)) + '</th><th>' + esc(t('Aspect', lang)) + '</th><th>' + esc(t('P2', lang)) + '</th><th>' + esc(t('Orb', lang)) + '</th><th>' + esc(t('Major', lang)) + '</th></tr></thead><tbody>';
      aspects.forEach(function (a) {
        html += '<tr>' +
          '<td>' + aspectBodyCellHtml(a.p1 || '-', reportCfg.showAspectGlyphs) + '</td>' +
          '<td>' + aspectTypeCellHtml(a, lang, reportCfg.showAspectGlyphs) + '</td>' +
          '<td>' + aspectBodyCellHtml(a.p2 || '-', reportCfg.showAspectGlyphs) + '</td>' +
          '<td>' + esc(typeof a.orb === 'number' ? a.orb.toFixed(2) : a.orb || '-') + '</td>' +
          '<td>' + esc(a.is_major ? t('Yes', lang) : t('No', lang)) + '</td>' +
          '</tr>';
      });
      html += '</tbody></table>';
    } else {
      html += '<p>' + esc(fmt('{count} aspects detected.', { count: aspects.length }, lang)) + '</p>';
    }
    html += '</section>';

    return html;
  }

  function setSuiteVariable(container, name, value) {
    container.style.setProperty(name, value);
    if (name.indexOf('--openastro-') === 0) {
      container.style.setProperty(name.replace('--openastro-', '--astrology-suite-'), value);
    }
  }

  function botSystemLabel(system) {
    return system === 'vedic' ? 'Vedic Astrologer' : 'Western Astrologer';
  }

  function botIntroText(system) {
    return system === 'vedic'
      ? 'Add your birth details, then ask about your Vedic chart, dashas, panchang, or timing.'
      : 'Add your birth details, then ask about your natal chart, transits, Moon, or daily timing.';
  }

  function botQuickPrompts(system) {
    if (system === 'vedic') {
      return [
        'What stands out in my Vedic chart?',
        'What dasha themes should I watch?',
        'What is today\'s panchang?'
      ];
    }
    return [
      'What stands out in my birth chart?',
      'What should I focus on today?',
      'What does my Moon need?'
    ];
  }

  function normalizeBotCity(item) {
    if (!item) {
      return null;
    }
    return {
      name: item.name || '',
      lat: Number(item.lat),
      lng: Number(item.lng),
      timezone: item.timezone || item.tz_str || '',
      country_code: item.country_code || item.country || '',
      country_name: item.country_name || item.country_full_name || '',
      state: cityRegionLabel(item),
      display_name: citySuggestionLabel(item)
    };
  }

  function mountAstrologyBot(container, cfg, lang) {
    var system = 'western';
    var selectedCity = null;
    var profile = null;
    var loading = false;
    var messages = [
      { role: 'assistant', content: botIntroText(system) }
    ];

    var shell = create('section', { class: 'openastro-bot' });
    var phone = create('div', { class: 'openastro-bot-phone' });
    var header = create('div', { class: 'openastro-bot-header' });
    var headerText = create('div', { class: 'openastro-bot-header-text' });
    var kicker = create('span', { class: 'openastro-bot-kicker' }, 'Astrology Bot');
    var title = create('h3', null, botSystemLabel(system));
    var statusPill = create('span', { class: 'openastro-bot-status-pill' }, 'Online');
    var contactRow = create('div', { class: 'openastro-bot-contacts', role: 'tablist', 'aria-label': 'Astrology bot system' });
    var westernButton = create('button', { type: 'button', class: 'openastro-bot-contact is-active', role: 'tab', 'aria-selected': 'true' }, 'Western');
    var vedicButton = create('button', { type: 'button', class: 'openastro-bot-contact', role: 'tab', 'aria-selected': 'false' }, 'Vedic');
    var screen = create('div', { class: 'openastro-bot-screen' });
    var profileForm = create('form', { class: 'openastro-bot-profile' });
    var profileTitle = create('h4', null, 'Birth profile');
    var profileGrid = create('div', { class: 'openastro-bot-profile-grid' });
    var dateLabel = create('label', { class: 'openastro-bot-field' });
    var timeLabel = create('label', { class: 'openastro-bot-field' });
    var cityLabelEl = create('label', { class: 'openastro-bot-field openastro-bot-city-field' });
    var birthDate = create('input', { type: 'date', class: 'openastro-input' });
    var birthTime = create('input', { type: 'time', class: 'openastro-input', step: '60' });
    var cityId = 'openastro-bot-city-' + Math.random().toString(36).slice(2, 10);
    var cityResultsId = cityId + '-results';
    var cityMessageId = cityId + '-message';
    var cityInput = create('input', {
      id: cityId,
      type: 'text',
      class: 'openastro-input openastro-city-input',
      placeholder: 'Birth city',
      role: 'combobox',
      'aria-autocomplete': 'list',
      'aria-haspopup': 'listbox',
      'aria-controls': cityResultsId,
      'aria-describedby': cityMessageId,
      'aria-expanded': 'false'
    });
    var cityWrap = create('div', { class: 'openastro-city-field openastro-bot-city-wrap' });
    var cityMessage = create('p', { id: cityMessageId, class: 'openastro-city-loading', 'aria-live': 'polite' }, '');
    var cityResults = create('div', { id: cityResultsId, class: 'openastro-results', role: 'listbox', 'aria-label': 'City suggestions' });
    var saveProfile = create('button', { type: 'submit', class: 'openastro-bot-profile-save' }, 'Save profile');
    var profileStatus = create('p', { class: 'openastro-bot-profile-status', 'aria-live': 'polite' }, '');
    var messagesEl = create('div', { class: 'openastro-bot-messages', 'aria-live': 'polite' });
    var quickRow = create('div', { class: 'openastro-bot-quick' });
    var composer = create('form', { class: 'openastro-bot-composer' });
    var input = create('input', { type: 'text', class: 'openastro-bot-input', placeholder: 'Ask the astrologer...', autocomplete: 'off' });
    var send = create('button', { type: 'submit', class: 'openastro-bot-send' }, 'Send');
    var chatStatus = create('p', { class: 'openastro-bot-chat-status', 'aria-live': 'polite' }, '');

    headerText.appendChild(kicker);
    headerText.appendChild(title);
    header.appendChild(headerText);
    header.appendChild(statusPill);
    contactRow.appendChild(westernButton);
    contactRow.appendChild(vedicButton);

    dateLabel.appendChild(create('span', null, 'Birth date'));
    dateLabel.appendChild(birthDate);
    timeLabel.appendChild(create('span', null, 'Birth time'));
    timeLabel.appendChild(birthTime);
    cityLabelEl.appendChild(create('span', null, 'Birth city'));
    cityWrap.appendChild(cityInput);
    cityWrap.appendChild(cityMessage);
    cityWrap.appendChild(cityResults);
    cityLabelEl.appendChild(cityWrap);
    profileGrid.appendChild(dateLabel);
    profileGrid.appendChild(timeLabel);
    profileGrid.appendChild(cityLabelEl);
    profileForm.appendChild(profileTitle);
    profileForm.appendChild(profileGrid);
    profileForm.appendChild(saveProfile);
    profileForm.appendChild(profileStatus);

    composer.appendChild(input);
    composer.appendChild(send);
    phone.appendChild(header);
    phone.appendChild(contactRow);
    screen.appendChild(profileForm);
    screen.appendChild(messagesEl);
    screen.appendChild(quickRow);
    screen.appendChild(composer);
    screen.appendChild(chatStatus);
    phone.appendChild(screen);
    shell.appendChild(phone);
    container.appendChild(shell);

    setupCityAutocomplete({
      input: cityInput,
      wrap: cityWrap,
      results: cityResults,
      message: cityMessage,
      resultsId: cityResultsId,
      onSelect: function (item) {
        selectedCity = normalizeBotCity(item);
        if (!selectedCity) {
          markProfileDirty();
        }
      }
    });

    function markProfileDirty() {
      if (!profile) {
        return;
      }
      profile = null;
      profileForm.classList.remove('is-saved');
      profileStatus.textContent = 'Save the updated birth profile before continuing.';
      syncComposerState();
    }

    function setSystem(nextSystem) {
      system = nextSystem === 'vedic' ? 'vedic' : 'western';
      title.textContent = botSystemLabel(system);
      westernButton.classList.toggle('is-active', system === 'western');
      vedicButton.classList.toggle('is-active', system === 'vedic');
      westernButton.setAttribute('aria-selected', system === 'western' ? 'true' : 'false');
      vedicButton.setAttribute('aria-selected', system === 'vedic' ? 'true' : 'false');
      messages = [{ role: 'assistant', content: botIntroText(system) }];
      renderMessages();
      renderQuickPrompts();
      chatStatus.textContent = '';
    }

    function renderMessages() {
      messagesEl.innerHTML = '';
      messages.forEach(function (message) {
        var bubble = create('div', { class: 'openastro-bot-message openastro-bot-message-' + (message.role === 'user' ? 'user' : 'assistant') });
        String(message.content || '').split(/\n{2,}/).forEach(function (paragraph) {
          bubble.appendChild(create('p', null, paragraph));
        });
        messagesEl.appendChild(bubble);
      });
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function renderQuickPrompts() {
      quickRow.innerHTML = '';
      botQuickPrompts(system).forEach(function (prompt) {
        var button = create('button', { type: 'button', class: 'openastro-bot-quick-btn' }, prompt);
        button.disabled = loading || !profile;
        button.addEventListener('click', function () {
          sendMessage(prompt);
        });
        quickRow.appendChild(button);
      });
    }

    function syncComposerState() {
      var disabled = loading || !profile;
      input.disabled = disabled;
      send.disabled = disabled;
      saveProfile.disabled = loading;
      statusPill.textContent = loading ? 'Thinking' : 'Online';
      renderQuickPrompts();
    }

    function saveBirthProfile() {
      if (!birthDate.value || !birthTime.value || !selectedCity) {
        profileStatus.textContent = 'Birth date, exact time, and a city from suggestions are required.';
        return false;
      }
      profile = {
        birthDate: birthDate.value,
        birthTime: birthTime.value,
        city: selectedCity
      };
      profileForm.classList.add('is-saved');
      profileStatus.textContent = 'Profile saved for this chat.';
      messages.push({ role: 'assistant', content: botSystemLabel(system) + ' is ready. Ask a chart or timing question.' });
      renderMessages();
      syncComposerState();
      input.focus();
      return true;
    }

    function sendMessage(text) {
      var content = String(text || '').trim();
      if (!content) {
        return;
      }
      if (!profile && !saveBirthProfile()) {
        return;
      }

      var history = messages.slice(-8);
      messages.push({ role: 'user', content: content });
      renderMessages();
      input.value = '';
      loading = true;
      chatStatus.textContent = 'Generating answer...';
      syncComposerState();

      api('/astrology-bot/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          system: system,
          message: content,
          history: history,
          profile: profile,
          language: lang
        })
      })
        .then(function (result) {
          if (!result.ok || !result.body) {
            messages.push({ role: 'assistant', content: (result.body && result.body.message) || 'The astrology bot could not answer right now.' });
            return;
          }
          messages.push({ role: 'assistant', content: result.body.reply || 'The astrology bot returned an empty answer.' });
        })
        .catch(function () {
          messages.push({ role: 'assistant', content: 'Unexpected error while generating the bot answer.' });
        })
        .finally(function () {
          loading = false;
          chatStatus.textContent = '';
          renderMessages();
          syncComposerState();
        });
    }

    westernButton.addEventListener('click', function () { setSystem('western'); });
    vedicButton.addEventListener('click', function () { setSystem('vedic'); });
    birthDate.addEventListener('input', markProfileDirty);
    birthTime.addEventListener('input', markProfileDirty);
    profileForm.addEventListener('submit', function (event) {
      event.preventDefault();
      saveBirthProfile();
    });
    composer.addEventListener('submit', function (event) {
      event.preventDefault();
      sendMessage(input.value);
    });

    renderMessages();
    renderQuickPrompts();
    syncComposerState();
  }

  function mountEphemeris(container, cfg) {
    var now = new Date();
    var selectedCity = null;
    var currentData = null;
    var selectedBodies = {};
    EPHEMERIS_DEFAULT_BODY_IDS.forEach(function (id) {
      selectedBodies[id] = true;
    });

    var shell = create('section', { class: 'openastro-ephemeris' });
    var intro = create('div', { class: 'openastro-ephemeris-intro' });
    intro.appendChild(create('span', { class: 'openastro-ephemeris-eyebrow' }, 'Astrology Tools'));
    intro.appendChild(create('h2', { class: 'openastro-ephemeris-title' }, 'Free Ephemeris Table'));

    var toolbar = create('form', { class: 'openastro-ephemeris-toolbar' });
    var nav = create('div', { class: 'openastro-ephemeris-nav' });
    var prev = create('button', { type: 'button', class: 'openastro-ephemeris-icon-btn', 'aria-label': 'Previous month' }, '‹');
    var month = create('input', { type: 'number', min: '1', max: '12', step: '1', class: 'openastro-input openastro-ephemeris-month', 'aria-label': 'Month' });
    var year = create('input', { type: 'number', min: '1800', max: '2100', step: '1', class: 'openastro-input openastro-ephemeris-year', 'aria-label': 'Year' });
    var monthTitle = create('span', { class: 'openastro-ephemeris-month-title' });
    var next = create('button', { type: 'button', class: 'openastro-ephemeris-icon-btn', 'aria-label': 'Next month' }, '›');
    var today = create('button', { type: 'button', class: 'openastro-ephemeris-secondary-btn' }, 'Today');
    var zodiac = create('select', { class: 'openastro-input openastro-ephemeris-zodiac', 'aria-label': 'Zodiac type' });
    zodiac.appendChild(create('option', { value: 'tropical' }, 'Tropical'));
    zodiac.appendChild(create('option', { value: 'sidereal' }, 'Sidereal'));

    month.value = String(now.getMonth() + 1);
    year.value = String(now.getFullYear());
    nav.appendChild(prev);
    nav.appendChild(month);
    nav.appendChild(year);
    nav.appendChild(monthTitle);
    nav.appendChild(next);
    nav.appendChild(today);
    nav.appendChild(zodiac);

    var cityId = 'openastro-ephemeris-city-' + Math.random().toString(36).slice(2, 10);
    var cityResultsId = 'openastro-ephemeris-city-results-' + Math.random().toString(36).slice(2, 10);
    var cityMessageId = 'openastro-ephemeris-city-message-' + Math.random().toString(36).slice(2, 10);
    var cityWrap = create('div', { class: 'openastro-city-field openastro-ephemeris-city-field' });
    var city = create('input', {
      id: cityId,
      type: 'text',
      class: 'openastro-input openastro-city-input',
      placeholder: 'Optional city for ASC / MC',
      role: 'combobox',
      'aria-autocomplete': 'list',
      'aria-haspopup': 'listbox',
      'aria-controls': cityResultsId,
      'aria-describedby': cityMessageId,
      'aria-expanded': 'false'
    });
    var cityMessage = create('p', { id: cityMessageId, class: 'openastro-city-loading', 'aria-live': 'polite' }, '');
    var cityResults = create('div', { id: cityResultsId, class: 'openastro-results', role: 'listbox', 'aria-label': 'City suggestions' });
    cityWrap.appendChild(city);
    cityWrap.appendChild(cityMessage);
    cityWrap.appendChild(cityResults);

    var actions = create('div', { class: 'openastro-ephemeris-actions' });
    var refresh = create('button', { type: 'submit', class: 'openastro-ephemeris-secondary-btn' }, 'Refresh');
    var download = create('button', { type: 'button', class: 'openastro-ephemeris-secondary-btn' }, 'Download');
    actions.appendChild(refresh);
    actions.appendChild(download);

    var toolbarNote = create('p', { class: 'openastro-ephemeris-note' }, 'Default ephemeris is geocentric at GMT 00:00. Select a city only when you want local points like ASC, MC, Vertex, and Part of Fortune.');
    toolbar.appendChild(nav);
    toolbar.appendChild(cityWrap);
    toolbar.appendChild(actions);
    toolbar.appendChild(toolbarNote);

    var filters = create('section', { class: 'openastro-ephemeris-filter' });
    var filterHeading = create('div', { class: 'openastro-ephemeris-filter-heading' });
    filterHeading.appendChild(create('h3', null, 'Bodies'));
    filterHeading.appendChild(create('p', null, 'Choose the columns shown in the month grid.'));
    filters.appendChild(filterHeading);

    var chipById = {};
    var groups = [];
    EPHEMERIS_BODIES.forEach(function (body) {
      if (groups.indexOf(body.group) === -1) {
        groups.push(body.group);
      }
    });

    groups.forEach(function (group) {
      var groupEl = create('div', { class: 'openastro-ephemeris-body-group' });
      groupEl.appendChild(create('h4', null, group));
      var chips = create('div', { class: 'openastro-ephemeris-body-chips' });
      EPHEMERIS_BODIES.filter(function (body) {
        return body.group === group;
      }).forEach(function (body) {
        var chip = create('button', {
          type: 'button',
          class: 'openastro-ephemeris-chip',
          'data-body-id': body.id,
          'aria-pressed': selectedBodies[body.id] ? 'true' : 'false'
        });
        chip.appendChild(create('span', { class: 'openastro-ephemeris-glyph' }, body.glyph));
        chip.appendChild(create('span', null, body.label));
        chipById[body.id] = chip;
        chips.appendChild(chip);
      });
      groupEl.appendChild(chips);
      filters.appendChild(groupEl);
    });
    filters.appendChild(create('p', { class: 'openastro-ephemeris-local-note' }, 'Coordinate-based points unlock when a city is selected in the top toolbar.'));

    var tableCard = create('section', { class: 'openastro-ephemeris-table-card' });
    var tableTop = create('div', { class: 'openastro-ephemeris-table-top' });
    var tableMeta = create('span', { class: 'openastro-ephemeris-table-meta' }, 'GMT 00:00');
    var tableTitle = create('h3', null, '');
    var rowBadge = create('span', { class: 'openastro-ephemeris-row-badge' }, '0 rows');
    tableTop.appendChild(tableMeta);
    tableTop.appendChild(tableTitle);
    tableTop.appendChild(rowBadge);
    var viewport = create('div', { class: 'openastro-ephemeris-table-viewport' });
    var status = create('p', { class: 'openastro-status openastro-ephemeris-status', 'aria-live': 'polite' }, '');
    tableCard.appendChild(tableTop);
    tableCard.appendChild(viewport);
    tableCard.appendChild(status);

    shell.appendChild(intro);
    shell.appendChild(toolbar);
    shell.appendChild(filters);
    shell.appendChild(tableCard);
    container.appendChild(shell);

    function getYear() {
      var value = Number(year.value || now.getFullYear());
      if (!Number.isFinite(value)) value = now.getFullYear();
      value = Math.max(1800, Math.min(2100, Math.round(value)));
      year.value = String(value);
      return value;
    }

    function getMonth() {
      var value = Number(month.value || (now.getMonth() + 1));
      if (!Number.isFinite(value)) value = now.getMonth() + 1;
      value = Math.max(1, Math.min(12, Math.round(value)));
      month.value = String(value);
      return value;
    }

    function updateMonthTitle() {
      tableTitle.textContent = ephemerisMonthTitle(getYear(), getMonth());
      monthTitle.textContent = ephemerisMonthTitle(getYear(), getMonth());
    }

    function selectedBodyIds() {
      return EPHEMERIS_BODIES.filter(function (body) {
        return !!selectedBodies[body.id];
      }).map(function (body) {
        return body.id;
      });
    }

    function selectedBodyApis() {
      return selectedBodyIds().map(function (id) {
        return ephemerisBodyById(id).api;
      });
    }

    function syncChips() {
      EPHEMERIS_BODIES.forEach(function (body) {
        var chip = chipById[body.id];
        if (!chip) {
          return;
        }
        var disabled = !!body.local && !selectedCity;
        if (disabled) {
          selectedBodies[body.id] = false;
        }
        chip.disabled = disabled;
        chip.classList.toggle('is-selected', !!selectedBodies[body.id]);
        chip.setAttribute('aria-pressed', selectedBodies[body.id] ? 'true' : 'false');
      });
    }

    function buildPayload() {
      return {
        year: getYear(),
        month: getMonth(),
        zodiac_type: zodiac.value === 'sidereal' ? 'sidereal' : 'tropical',
        bodies: selectedBodyApis(),
        city: selectedCity ? selectedCity.name : '',
        lat: selectedCity ? selectedCity.lat : '',
        lng: selectedCity ? selectedCity.lng : '',
        tz_str: selectedCity ? cityTimezoneLabel(selectedCity) || 'AUTO' : ''
      };
    }

    function setLoading(loading) {
      refresh.disabled = loading;
      prev.disabled = loading;
      next.disabled = loading;
      today.disabled = loading;
      status.textContent = loading ? 'Loading ephemeris...' : status.textContent;
    }

    function renderEphemeris(data, payload) {
      var rows = Array.isArray(data && data.rows) ? data.rows : [];
      currentData = data;
      tableMeta.textContent = payload.lat !== '' && payload.lng !== ''
        ? ((payload.tz_str && payload.tz_str !== 'AUTO') ? payload.tz_str : 'Local time')
        : 'GMT 00:00';
      tableTitle.textContent = ephemerisMonthTitle(payload.year, payload.month);
      rowBadge.textContent = rows.length + ' row' + (rows.length === 1 ? '' : 's');
      viewport.innerHTML = ephemerisTableHtml(data);
      status.textContent = rows.length ? 'Done.' : 'No ephemeris rows returned.';
      window.dispatchEvent(new CustomEvent('openastro:ephemeris:generated', {
        detail: {
          request: payload,
          response: data
        }
      }));
    }

    function loadEphemeris() {
      updateMonthTitle();
      syncChips();
      if (!selectedBodyIds().length) {
        status.textContent = 'Select at least one body.';
        return;
      }

      var payload = buildPayload();
      setLoading(true);
      api('/western/ephemeris', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (result) {
          if (!result.ok || !result.body) {
            status.textContent = (result.body && result.body.message) || 'Could not load ephemeris.';
            return;
          }
          renderEphemeris(result.body, payload);
        })
        .catch(function () {
          status.textContent = 'Unexpected error while loading ephemeris.';
        })
        .finally(function () {
          setLoading(false);
        });
    }

    setupCityAutocomplete({
      input: city,
      wrap: cityWrap,
      results: cityResults,
      message: cityMessage,
      resultsId: cityResultsId,
      onSelect: function (item) {
        selectedCity = item;
        syncChips();
      }
    });

    Object.keys(chipById).forEach(function (id) {
      chipById[id].addEventListener('click', function () {
        var body = ephemerisBodyById(id);
        if (!body || (body.local && !selectedCity)) {
          return;
        }
        selectedBodies[id] = !selectedBodies[id];
        syncChips();
        loadEphemeris();
      });
    });

    toolbar.addEventListener('submit', function (event) {
      event.preventDefault();
      loadEphemeris();
    });

    function shiftMonth(delta) {
      var nextMonth = getMonth() + delta;
      var nextYear = getYear();
      if (nextMonth < 1) {
        nextMonth = 12;
        nextYear -= 1;
      } else if (nextMonth > 12) {
        nextMonth = 1;
        nextYear += 1;
      }
      year.value = String(Math.max(1800, Math.min(2100, nextYear)));
      month.value = String(nextMonth);
      loadEphemeris();
    }

    prev.addEventListener('click', function () {
      shiftMonth(-1);
    });
    next.addEventListener('click', function () {
      shiftMonth(1);
    });
    today.addEventListener('click', function () {
      var todayDate = new Date();
      year.value = String(todayDate.getFullYear());
      month.value = String(todayDate.getMonth() + 1);
      loadEphemeris();
    });
    zodiac.addEventListener('change', loadEphemeris);
    month.addEventListener('change', loadEphemeris);
    year.addEventListener('change', loadEphemeris);
    download.addEventListener('click', function () {
      if (!currentData) {
        status.textContent = 'Load ephemeris before downloading.';
        return;
      }
      var payload = buildPayload();
      downloadEphemerisCsv(currentData, 'ephemeris-' + payload.year + '-' + pad2(payload.month) + '.csv');
    });

    updateMonthTitle();
    syncChips();
    loadEphemeris();
  }

  function vedicNumber(value, digits) {
    var number = Number(value);
    return Number.isFinite(number) ? number.toFixed(typeof digits === 'number' ? digits : 2) : '—';
  }

  function vedicOrdinalText(value) {
    return String(value || '').replace(/\b(\d+)(?:st|nd|rd|th)\b/gi, function (match, digits) {
      var number = Number(digits);
      var lastTwo = number % 100;
      var suffix = lastTwo >= 11 && lastTwo <= 13
        ? 'th'
        : ({ 1: 'st', 2: 'nd', 3: 'rd' }[number % 10] || 'th');
      return digits + suffix;
    });
  }

  function vedicPlanet(chart, name) {
    var planets = chart && Array.isArray(chart.planets) ? chart.planets : [];
    var target = String(name || '').toLowerCase();
    for (var i = 0; i < planets.length; i += 1) {
      if (String(planets[i].name || '').toLowerCase() === target) {
        return planets[i];
      }
    }
    return {};
  }

  function vedicChartHtml(svg, reportCfg) {
    var printButton = reportCfg && reportCfg.showPrintButton
      ? '<button type="button" class="' + expandClassNames('openastro-print-btn') + '" data-openastro-print-chart="1">Print</button>'
      : '';
    return '<section class="' + expandClassNames('openastro-card openastro-chart-card openastro-vedic-chart-card') + '">' +
      '<h3>Vedic divisional chart</h3>' + printButton +
      '<div class="' + expandClassNames('openastro-chart openastro-vedic-chart') + '">' +
      (svg || '<p>Visual chart unavailable.</p>') + '</div></section>';
  }

  function vedicOverviewHtml(response) {
    var data = response && response.data && typeof response.data === 'object' ? response.data : (response || {});
    var chart = data.chart || {};
    var asc = chart.ascendant || {};
    var moon = vedicPlanet(chart, 'Moon');
    var sun = vedicPlanet(chart, 'Sun');
    var sadeSati = chart.sade_sati || (data.yogas && data.yogas.sade_sati) || {};
    var tabs = [];
    var tabPrefix = 'openastro-vedic-tab-' + Math.random().toString(36).slice(2, 9);

    function addTab(id, label, content) {
      if (content) {
        tabs.push({ id: id, label: label, content: content });
      }
    }

    var html = '<section class="' + expandClassNames('openastro-card openastro-vedic-report') + '">';
    html += '<div class="' + expandClassNames('openastro-vedic-heading') + '"><div><span class="' + expandClassNames('openastro-vedic-eyebrow') + '">Sidereal overview</span><h3>Vedic chart analysis</h3></div>';
    html += '<span class="' + expandClassNames('openastro-vedic-badge') + '">' + esc(String(data.ayanamsha || 'lahiri').toUpperCase()) + '</span></div>';
    html += '<div class="' + expandClassNames('openastro-vedic-summary-grid') + '">';
    html += '<article><span>Lagna</span><strong>' + esc(asc.sign || '—') + '</strong><small>' + esc(asc.nakshatra && asc.nakshatra.name ? asc.nakshatra.name + ' · Pada ' + (asc.nakshatra.pada || '—') : vedicNumber(asc.degree, 2) + '°') + '</small></article>';
    html += '<article><span>Chandra</span><strong>' + esc(moon.sign || '—') + '</strong><small>' + esc((moon.nakshatra || '—') + (moon.pada ? ' · Pada ' + moon.pada : '')) + '</small></article>';
    html += '<article><span>Surya</span><strong>' + esc(sun.sign || '—') + '</strong><small>House ' + esc(sun.house || '—') + ' · ' + esc(vedicNumber(sun.degree_in_sign, 2)) + '°</small></article>';
    html += '<article><span>Sade Sati</span><strong>' + esc(sadeSati.active ? (sadeSati.phase || 'Active') : 'Not active') + '</strong><small>' + esc(sadeSati.description || 'Saturn cycle status') + '</small></article>';
    html += '</div>';

    var planets = Array.isArray(chart.planets) ? chart.planets : [];
    if (planets.length) {
      var hasAvastha = planets.some(function (planet) { return planet && planet.avastha && planet.avastha.state; });
      var grahaHtml = '<div class="' + expandClassNames('openastro-vedic-table-wrap') + '"><table><thead><tr><th>Graha</th><th>Rashi</th><th>Degree</th><th>Bhava</th><th>Nakshatra</th><th>Pada</th>' + (hasAvastha ? '<th>Baladi Avastha</th>' : '') + '</tr></thead><tbody>';
      planets.forEach(function (planet) {
        var avastha = planet.avastha || {};
        grahaHtml += '<tr><td><strong>' + esc(planet.name || '—') + (planet.is_retrograde ? ' ℞' : '') + '</strong></td><td>' + esc(planet.sign || '—') + '</td><td>' + esc(vedicNumber(planet.degree_in_sign, 2)) + '°</td><td>' + esc(planet.house || '—') + '</td><td>' + esc(planet.nakshatra || '—') + '</td><td>' + esc(planet.pada || '—') + '</td>' + (hasAvastha ? '<td><strong>' + esc(avastha.state || '—') + '</strong>' + (avastha.quality ? '<small> · ' + esc(avastha.quality) + '</small>' : '') + '</td>' : '') + '</tr>';
      });
      grahaHtml += '</tbody></table></div>';
      addTab('grahas', 'Grahas', grahaHtml);
    }

    var dasha = data.vimshottari_dasha || {};
    var activePeriods = Array.isArray(dasha.active_periods) ? dasha.active_periods : [];
    if (activePeriods.length) {
      var dashaHtml = '<div class="' + expandClassNames('openastro-vedic-dasha-grid') + '">';
      activePeriods.forEach(function (period) {
        var progress = Math.max(0, Math.min(100, Number(period.progress_fraction || 0) * 100));
        dashaHtml += '<article><span>' + esc(period.level || 'Dasha') + '</span><strong>' + esc(period.lord || '—') + '</strong><small>' + esc(period.start || '—') + ' → ' + esc(period.end || '—') + '</small><div class="' + expandClassNames('openastro-vedic-progress') + '"><i style="width:' + progress.toFixed(1) + '%"></i></div></article>';
      });
      dashaHtml += '</div>';
      addTab('dashas', 'Dashas', dashaHtml);
    }

    var yogaData = data.yogas || {};
    var yogas = Array.isArray(yogaData.yogas) ? yogaData.yogas.filter(function (yoga) { return yoga && yoga.active; }) : [];
    if (yogas.length) {
      var yogaHtml = '<div class="' + expandClassNames('openastro-vedic-yoga-grid') + '">';
      yogas.forEach(function (yoga) {
        var evidence = Array.isArray(yoga.evidence) ? yoga.evidence : [];
        yogaHtml += '<article><div><span>' + esc(yoga.category || yoga.type || 'Yoga') + '</span>' + (yoga.strength ? '<em>' + esc(yoga.strength) + '</em>' : '') + '</div><h4>' + esc(yoga.name || 'Yoga') + '</h4><p>' + esc(yoga.description || '') + '</p>';
        if (evidence.length) {
          yogaHtml += '<details><summary>Evidence</summary><ul>' + evidence.map(function (item) { return '<li>' + esc(vedicOrdinalText(item.message || item.kind || '')) + '</li>'; }).join('') + '</ul></details>';
        }
        yogaHtml += '</article>';
      });
      yogaHtml += '</div>';
      addTab('yogas', 'Yogas', yogaHtml);
    }

    var panchang = data.panchang || {};
    if (panchang.date) {
      var panchangItems = [
        ['Tithi', panchang.tithi && panchang.tithi.name, panchang.tithi && panchang.tithi.paksha],
        ['Nakshatra', panchang.nakshatra && panchang.nakshatra.name, panchang.nakshatra && panchang.nakshatra.lord],
        ['Yoga', panchang.yoga && panchang.yoga.name, panchang.yoga && panchang.yoga.ends_at],
        ['Weekday', panchang.weekday && panchang.weekday.name, panchang.date],
        ['Sunrise', panchang.sunrise, 'Sunset ' + (panchang.sunset || '—')],
        ['Rahu Kalam', panchang.rahu_kalam && panchang.rahu_kalam.start, panchang.rahu_kalam && panchang.rahu_kalam.end]
      ];
      var panchangHtml = '<div class="' + expandClassNames('openastro-vedic-panchang-grid') + '">';
      panchangItems.forEach(function (item) {
        panchangHtml += '<article><span>' + esc(item[0]) + '</span><strong>' + esc(item[1] || '—') + '</strong><small>' + esc(item[2] || '') + '</small></article>';
      });
      panchangHtml += '</div>';
      addTab('panchang', 'Panchang', panchangHtml);
    }

    var shadbala = data.shadbala && typeof data.shadbala === 'object' ? data.shadbala : {};
    var strengthNames = Object.keys(shadbala).filter(function (name) { return shadbala[name] && typeof shadbala[name] === 'object'; });
    if (strengthNames.length) {
      var strengthHtml = '<div class="' + expandClassNames('openastro-vedic-table-wrap') + '"><table><thead><tr><th>Graha</th><th>Rupas</th><th>Minimum</th><th>Ratio</th><th>Assessment</th></tr></thead><tbody>';
      strengthNames.forEach(function (name) {
        var strength = shadbala[name];
        var ratio = Number(strength.ratio || 0);
        strengthHtml += '<tr><td><strong>' + esc(name) + '</strong></td><td>' + esc(vedicNumber(strength.shadbala_in_rupas, 2)) + '</td><td>' + esc(vedicNumber(strength.minimum_requirements, 2)) + '</td><td>' + esc(vedicNumber(ratio, 2)) + '</td><td><span class="' + expandClassNames('openastro-vedic-strength ' + (ratio >= 1 ? 'is-sufficient' : 'is-low')) + '">' + (ratio >= 1 ? 'Meets minimum' : 'Below minimum') + '</span></td></tr>';
      });
      strengthHtml += '</tbody></table></div>';
      addTab('strength', 'Strength', strengthHtml);
    }

    var ashtaka = data.ashtakavarga || {};
    var sarva = Array.isArray(ashtaka.sarvashtakavarga) ? ashtaka.sarvashtakavarga : [];
    if (sarva.length === 12) {
      var signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
      var ashtakaHtml = '<p class="openastro-vedic-helper">Total points: <strong>' + esc(ashtaka.total_points || sarva.reduce(function (sum, value) { return sum + Number(value || 0); }, 0)) + '</strong></p><div class="' + expandClassNames('openastro-vedic-ashtaka-grid') + '">';
      sarva.forEach(function (points, index) {
        ashtakaHtml += '<article><span>' + esc(signs[index]) + '</span><strong>' + esc(points) + '</strong></article>';
      });
      ashtakaHtml += '</div>';
      addTab('ashtakavarga', 'Ashtakavarga', ashtakaHtml);
    }

    var vargaRoot = data.vargas && data.vargas.vargas ? data.vargas.vargas : {};
    var vargaNames = Object.keys(vargaRoot);
    if (vargaNames.length) {
      var vargaHtml = '<div class="' + expandClassNames('openastro-vedic-varga-grid') + '">';
      vargaNames.forEach(function (key) {
        var varga = vargaRoot[key] || {};
        var placements = Array.isArray(varga.planets) ? varga.planets : [];
        vargaHtml += '<article><div><strong>' + esc(key) + ' · ' + esc(varga.name || 'Divisional chart') + '</strong><span>Lagna ' + esc(varga.ascendant && varga.ascendant.sign ? varga.ascendant.sign : '—') + '</span></div><p>' + placements.map(function (planet) { return esc((planet.name || '—') + ' ' + (planet.sign || '—') + ' H' + (planet.house || '—')); }).join(' · ') + '</p></article>';
      });
      vargaHtml += '</div>';
      addTab('vargas', 'Vargas', vargaHtml);
    }

    if (tabs.length) {
      html += '<div class="' + expandClassNames('openastro-vedic-result-tabs') + '" role="tablist" aria-label="Vedic result sections">';
      tabs.forEach(function (tab, index) {
        var tabId = tabPrefix + '-' + tab.id;
        html += '<button type="button" role="tab" id="' + esc(tabId + '-tab') + '" aria-controls="' + esc(tabId) + '" aria-selected="' + (index === 0 ? 'true' : 'false') + '" tabindex="' + (index === 0 ? '0' : '-1') + '" class="' + expandClassNames('openastro-vedic-result-tab' + (index === 0 ? ' is-active' : '')) + '" data-openastro-vedic-tab="' + esc(tab.id) + '">' + esc(tab.label) + '</button>';
      });
      html += '</div><div class="' + expandClassNames('openastro-vedic-result-panels') + '">';
      tabs.forEach(function (tab, index) {
        var tabId = tabPrefix + '-' + tab.id;
        html += '<section role="tabpanel" id="' + esc(tabId) + '" aria-labelledby="' + esc(tabId + '-tab') + '" class="' + expandClassNames('openastro-vedic-section openastro-vedic-result-panel') + '" data-openastro-vedic-panel="' + esc(tab.id) + '"' + (index === 0 ? '' : ' hidden') + '>' + tab.content + '</section>';
      });
      html += '</div>';
    }

    html += '<p class="' + expandClassNames('openastro-vedic-disclaimer') + '">Astrology-oriented information for reflection; it is not medical, legal, financial, or psychological advice.</p>';
    html += '</section>';
    return html;
  }

  function mountVedic(container, cfg, mode) {
    var workbench = mode === 'vedic-workbench';
    var vedicCfg = cfg.vedic || {};
    var presetOverride = container.getAttribute('data-vedic-preset') || '';
    var layoutOverride = container.getAttribute('data-vedic-layout') || '';
    var divisionsOverride = (container.getAttribute('data-vedic-divisions') || '').split(',').map(Number).filter(function (value) { return value > 0; });
    var hasModulesOverride = container.hasAttribute('data-vedic-modules');
    var modulesOverride = (container.getAttribute('data-vedic-modules') || '').split(',').filter(Boolean);
    var defaultPreset = ['guided', 'practitioner', 'custom'].indexOf(presetOverride) !== -1
      ? presetOverride
      : (['guided', 'practitioner', 'custom'].indexOf(vedicCfg.preset) !== -1 ? vedicCfg.preset : 'guided');
    var calculationDivisions = Array.isArray(vedicCfg.calculationDivisions) && vedicCfg.calculationDivisions.length
      ? vedicCfg.calculationDivisions.map(Number)
      : VEDIC_CALCULATION_DIVISIONS;
    var allowedDivisionIds = workbench ? calculationDivisions : VEDIC_DIVISIONS.map(function (item) { return item.id; });
    var availableDivisions = VEDIC_DIVISIONS.filter(function (division) {
      return allowedDivisionIds.indexOf(division.id) !== -1;
    });
    var configuredDivisions = divisionsOverride.length ? divisionsOverride : (Array.isArray(vedicCfg.defaultDivisions) ? vedicCfg.defaultDivisions.map(Number) : [1, 9]);
    var shell = create('section', { class: 'openastro-vedic' });
    var intro = create('div', { class: 'openastro-vedic-intro' });
    intro.appendChild(create('span', { class: 'openastro-vedic-eyebrow' }, workbench ? 'For Vedic astrologers and curious users' : 'Sidereal chart renderer'));
    intro.appendChild(create('h2', null, workbench ? 'Vedic Astrology Workbench' : 'Vedic Visual Chart'));
    intro.appendChild(create('p', null, workbench
      ? 'Generate regional Kundli layouts with a professional reference for grahas, vargas, dashas, yogas, Panchang, and strength.'
      : 'Generate D1, D9, and other supported divisional charts in North, South, or East Indian style.'));

    var form = create('form', { class: 'openastro-form openastro-vedic-form' });
    var preset = create('select', { class: 'openastro-input', 'aria-label': 'Vedic detail preset' });
    preset.appendChild(create('option', { value: 'guided' }, 'Guided · essentials'));
    preset.appendChild(create('option', { value: 'practitioner' }, 'Practitioner · full reference'));
    preset.appendChild(create('option', { value: 'custom' }, 'Custom · use selections'));
    var date = create('input', { type: 'date', class: 'openastro-input', 'aria-label': 'Birth date' });
    var time = createTimeControls('en');
    var cityId = 'openastro-vedic-city-' + Math.random().toString(36).slice(2, 10);
    var resultsId = 'openastro-vedic-city-results-' + Math.random().toString(36).slice(2, 10);
    var messageId = 'openastro-vedic-city-message-' + Math.random().toString(36).slice(2, 10);
    var city = create('input', { id: cityId, type: 'text', class: 'openastro-input openastro-city-input', placeholder: 'Birth city or city, country code', 'aria-label': 'Birth city', role: 'combobox', 'aria-autocomplete': 'list', 'aria-haspopup': 'listbox', 'aria-controls': resultsId, 'aria-describedby': messageId, 'aria-expanded': 'false' });
    var cityWrap = create('div', { class: 'openastro-city-field' });
    var cityMessage = create('p', { id: messageId, class: 'openastro-city-loading', 'aria-live': 'polite' }, '');
    var cityResults = create('div', { id: resultsId, class: 'openastro-results', role: 'listbox', 'aria-label': 'City suggestions' });
    cityWrap.appendChild(city);
    cityWrap.appendChild(cityMessage);
    cityWrap.appendChild(cityResults);

    var chartControls = create('div', { class: 'openastro-vedic-control-grid' });
    var chartStyle = create('select', { class: 'openastro-input', 'aria-label': 'Kundli layout' });
    [['north_indian', 'North Indian'], ['south_indian', 'South Indian'], ['east_indian', 'East Indian']].forEach(function (option) { chartStyle.appendChild(create('option', { value: option[0] }, option[1] + ' layout')); });
    var theme = create('select', { class: 'openastro-input', 'aria-label': 'Chart theme' });
    [['light', 'Light'], ['dark', 'Dark'], ['mono', 'Monochrome']].forEach(function (option) { theme.appendChild(create('option', { value: option[0] }, option[1] + ' chart')); });
    chartControls.appendChild(chartStyle);
    chartControls.appendChild(theme);

    var divisionField = create('fieldset', { class: 'openastro-vedic-options' });
    divisionField.appendChild(create('legend', null, 'Divisional charts'));
    var divisionChecks = {};
    var extraDivisions = create('details', { class: 'openastro-vedic-advanced' });
    extraDivisions.appendChild(create('summary', null, 'More divisional charts'));
    var extraDivisionGrid = create('div', { class: 'openastro-vedic-division-grid' });
    availableDivisions.forEach(function (division) {
      var label = create('label', { class: 'openastro-inline' });
      var check = create('input', { type: 'checkbox', value: String(division.id), checked: configuredDivisions.indexOf(division.id) !== -1 });
      divisionChecks[division.id] = check;
      label.appendChild(check);
      label.appendChild(document.createTextNode(division.label));
      if (division.id === 1 || division.id === 9) {
        divisionField.appendChild(label);
      } else {
        extraDivisionGrid.appendChild(label);
      }
    });
    extraDivisions.appendChild(extraDivisionGrid);
    divisionField.appendChild(extraDivisions);
    divisionField.appendChild(create('small', null, workbench
      ? 'The workbench calculates every selected varga. Choose up to four visual panels.'
      : 'The visual canvas renders the first four selected divisions.'));

    var advanced = create('details', { class: 'openastro-vedic-advanced' });
    advanced.appendChild(create('summary', null, 'Practitioner controls'));
    var advancedGrid = create('div', { class: 'openastro-vedic-control-grid' });
    var nodeType = create('select', { class: 'openastro-input', 'aria-label': 'Lunar node type' });
    nodeType.appendChild(create('option', { value: 'mean' }, 'Mean Rahu / Ketu'));
    nodeType.appendChild(create('option', { value: 'true' }, 'True Rahu / Ketu'));
    var signLabels = create('select', { class: 'openastro-input', 'aria-label': 'Sign label style' });
    [['number_abbr', 'Sign number + abbreviation'], ['abbr', 'Sign abbreviations'], ['astrofont_number', 'Sign glyph + number'], ['glyph', 'Sign glyphs']].forEach(function (option) { signLabels.appendChild(create('option', { value: option[0] }, option[1])); });
    var bodyLabels = create('select', { class: 'openastro-input', 'aria-label': 'Graha label style' });
    [['abbr', 'Graha abbreviations'], ['glyph', 'Graha glyphs'], ['visual', 'Visual planet markers']].forEach(function (option) { bodyLabels.appendChild(create('option', { value: option[0] }, option[1])); });
    var outerLabel = create('label', { class: 'openastro-inline' });
    var outerPlanets = create('input', { type: 'checkbox' });
    outerLabel.appendChild(outerPlanets);
    outerLabel.appendChild(document.createTextNode('Include Uranus, Neptune, and Pluto'));
    advancedGrid.appendChild(nodeType);
    advancedGrid.appendChild(signLabels);
    advancedGrid.appendChild(bodyLabels);
    advancedGrid.appendChild(outerLabel);
    advanced.appendChild(advancedGrid);

    chartStyle.value = layoutOverride || vedicCfg.chartStyle || 'north_indian';
    theme.value = vedicCfg.themeType || 'light';
    nodeType.value = vedicCfg.nodeType || 'mean';
    signLabels.value = vedicCfg.signLabelMode || 'number_abbr';
    bodyLabels.value = vedicCfg.bodyLabelMode || 'abbr';
    outerPlanets.checked = !!vedicCfg.includeOuterPlanets;
    preset.value = defaultPreset;

    var referenceDate = create('input', { type: 'date', class: 'openastro-input', value: dateTodayValue(), 'aria-label': 'Dasha reference date' });
    var referenceWrap = create('label', { class: 'openastro-vedic-control' });
    referenceWrap.appendChild(create('span', null, 'Dasha reference date'));
    referenceWrap.appendChild(referenceDate);
    var dashaLevels = create('select', { class: 'openastro-input', 'aria-label': 'Dasha depth' });
    [['0', 'Dasha off'], ['1', 'Mahadasha'], ['2', 'Mahadasha + Antardasha'], ['3', 'Through Pratyantardasha']].forEach(function (option) { dashaLevels.appendChild(create('option', { value: option[0] }, option[1])); });
    dashaLevels.value = String(typeof vedicCfg.dashaLevels === 'number' ? vedicCfg.dashaLevels : 2);
    var dashaWrap = create('label', { class: 'openastro-vedic-control' });
    dashaWrap.appendChild(create('span', null, 'Dasha depth'));
    dashaWrap.appendChild(dashaLevels);
    var moduleField = create('fieldset', { class: 'openastro-vedic-options openastro-vedic-modules' });
    moduleField.appendChild(create('legend', null, 'Calculation modules'));
    var modules = {};
    var moduleDefaults = vedicCfg.modules && typeof vedicCfg.modules === 'object' ? vedicCfg.modules : {};
    VEDIC_MODULES.forEach(function (item) {
      var label = create('label', { class: 'openastro-inline' });
      var checked = hasModulesOverride
        ? modulesOverride.indexOf(item.id) !== -1
        : !!moduleDefaults[item.id];
      var check = create('input', { type: 'checkbox', checked: checked });
      modules[item.field] = check;
      label.appendChild(check);
      label.appendChild(document.createTextNode(item.label));
      moduleField.appendChild(label);
    });
    var workbenchOptions = create('details', { class: 'openastro-vedic-advanced openastro-vedic-workbench-options' });
    workbenchOptions.appendChild(create('summary', null, 'Workbench modules'));
    var workbenchControlGrid = create('div', { class: 'openastro-vedic-control-grid' });
    workbenchControlGrid.appendChild(referenceWrap);
    workbenchControlGrid.appendChild(dashaWrap);
    workbenchOptions.appendChild(workbenchControlGrid);
    workbenchOptions.appendChild(moduleField);

    var submit = create('button', { type: 'submit', class: 'openastro-btn' }, workbench ? 'Generate Vedic workbench' : 'Generate Vedic chart');
    var status = create('p', { class: 'openastro-status', 'aria-live': 'polite' }, 'Exact birth time is required for Lagna, bhavas, and divisional charts.');
    var output = create('div', { class: 'openastro-output openastro-vedic-output' });

    form.appendChild(preset);
    form.appendChild(date);
    form.appendChild(time.wrap);
    form.appendChild(cityWrap);
    form.appendChild(chartControls);
    form.appendChild(divisionField);
    if (workbench) {
      form.appendChild(workbenchOptions);
    }
    form.appendChild(advanced);
    form.appendChild(submit);
    form.appendChild(status);
    shell.appendChild(intro);
    shell.appendChild(form);
    shell.appendChild(output);
    container.appendChild(shell);

    var selectedCity = null;
    setupCityAutocomplete({ input: city, wrap: cityWrap, results: cityResults, message: cityMessage, resultsId: resultsId, onSelect: function (item) { selectedCity = item; } });

    function setPreset(value) {
      var practitioner = value === 'practitioner';
      var custom = value === 'custom';
      var presetDivisions = practitioner
        ? [1, 9, 10]
        : (custom ? configuredDivisions : [1, 9]);
      var presetModules = custom
        ? (hasModulesOverride ? modulesOverride : VEDIC_MODULES.filter(function (item) { return !!moduleDefaults[item.id]; }).map(function (item) { return item.id; }))
        : builtInModuleIds(practitioner);

      Object.keys(divisionChecks).forEach(function (division) {
        divisionChecks[division].checked = presetDivisions.indexOf(Number(division)) !== -1;
      });
      VEDIC_MODULES.forEach(function (item) {
        modules[item.field].checked = presetModules.indexOf(item.id) !== -1;
      });
      dashaLevels.value = custom
        ? String(typeof vedicCfg.dashaLevels === 'number' ? vedicCfg.dashaLevels : 2)
        : (practitioner ? '3' : '2');
      outerPlanets.checked = practitioner;
      advanced.open = practitioner;
      extraDivisions.open = practitioner || custom;
      if (workbench) {
        workbenchOptions.open = practitioner || custom;
      }
    }

    function builtInModuleIds(practitioner) {
      return practitioner
        ? ['yogas', 'panchang', 'shadbala', 'ashtakavarga', 'avastha']
        : ['yogas', 'panchang'];
    }

    preset.addEventListener('change', function () { setPreset(preset.value); });
    setPreset(defaultPreset);

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var timeValue = time.value();
      if (!date.value || !timeValue || !selectedCity) {
        status.textContent = 'Birth date, exact time, and a city selected from suggestions are required.';
        return;
      }
      var dateParts = date.value.split('-');
      var timeParts = timeValue.split(':');
      var divisions = Object.keys(divisionChecks).filter(function (key) { return divisionChecks[key].checked; }).map(Number).sort(function (a, b) { return a - b; });
      if (!divisions.length) {
        status.textContent = 'Select at least one divisional chart.';
        return;
      }
      if (!workbench && divisions.length > 4) {
        status.textContent = 'Select up to four divisional charts for one visual canvas.';
        return;
      }
      var common = {
        year: Number(dateParts[0]), month: Number(dateParts[1]), day: Number(dateParts[2]),
        hour: Number(timeParts[0]), minute: Number(timeParts[1]),
        city: selectedCity.name || '', lat: Number(selectedCity.lat), lng: Number(selectedCity.lng),
        tz_str: cityTimezoneLabel(selectedCity) || 'AUTO', ayanamsha: vedicCfg.ayanamsha || 'lahiri', house_system: 'whole_sign',
        node_type: nodeType.value
      };
      var visualPayload = Object.assign({}, common, {
        divisions: divisions,
        chart_style: chartStyle.value,
        theme_type: theme.value,
        sign_label_mode: signLabels.value,
        body_label_mode: bodyLabels.value,
        include_outer_planets: outerPlanets.checked,
        size: Number(vedicCfg.size || 700)
      });
      var calculatePayload = Object.assign({}, common, {
        vargas: divisions,
        reference_date: referenceDate.value || dateTodayValue(),
        dasha_levels: Number(dashaLevels.value),
        include_yogas: modules.include_yogas.checked,
        include_panchang: modules.include_panchang.checked,
        include_shadbala: modules.include_shadbala.checked,
        include_ashtakavarga: modules.include_ashtakavarga.checked,
        include_avastha: modules.include_avastha.checked
      });
      submit.disabled = true;
      status.textContent = 'Calculating the sidereal chart…';
      output.innerHTML = '';

      var visualRequest = api('/vedic/visual-chart', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(visualPayload) });
      if (!workbench) {
        visualRequest.then(function (result) {
          if (!result.ok || !result.body || !result.body.svg) {
            status.textContent = result.body && result.body.message ? result.body.message : 'Could not generate the Vedic visual chart.';
            return;
          }
          output.innerHTML = vedicChartHtml(result.body.svg, cfg.report);
          status.textContent = 'Vedic chart ready.';
          window.dispatchEvent(new CustomEvent('openastro:vedic:generated', { detail: { request: visualPayload, chartSvg: result.body.svg } }));
        }).catch(function () {
          status.textContent = 'Unexpected error while generating the Vedic chart.';
        }).finally(function () { submit.disabled = false; });
        return;
      }

      var calculateRequest = api('/vedic/calculate', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(calculatePayload) });
      Promise.all([visualRequest, calculateRequest]).then(function (results) {
        var visual = results[0];
        var calculation = results[1];
        var svg = visual.ok && visual.body && visual.body.svg ? visual.body.svg : '';
        if (!calculation.ok || !calculation.body) {
          output.innerHTML = svg ? vedicChartHtml(svg, cfg.report) : '';
          status.textContent = calculation.body && calculation.body.message ? calculation.body.message : 'Could not generate the Vedic calculation.';
          return;
        }
        output.innerHTML = vedicChartHtml(svg, cfg.report) + vedicOverviewHtml(calculation.body);
        status.textContent = svg ? 'Vedic workbench ready.' : 'Calculation ready. Visual chart unavailable.';
        window.dispatchEvent(new CustomEvent('openastro:vedic:generated', { detail: { request: calculatePayload, visualRequest: visualPayload, response: calculation.body, chartSvg: svg || null } }));
      }).catch(function () {
        status.textContent = 'Unexpected error while generating the Vedic workbench.';
      }).finally(function () { submit.disabled = false; });
    });

    function activateVedicResultTab(button, moveFocus) {
      var report = button.closest('.openastro-vedic-report');
      if (!report) return;
      var id = button.getAttribute('data-openastro-vedic-tab');
      report.querySelectorAll('[data-openastro-vedic-tab]').forEach(function (tabButton) {
        var isActive = tabButton === button;
        tabButton.classList.toggle('is-active', isActive);
        tabButton.setAttribute('aria-selected', isActive ? 'true' : 'false');
        tabButton.setAttribute('tabindex', isActive ? '0' : '-1');
      });
      report.querySelectorAll('[data-openastro-vedic-panel]').forEach(function (panel) {
        panel.hidden = panel.getAttribute('data-openastro-vedic-panel') !== id;
      });
      if (moveFocus) {
        button.focus();
      }
    }

    output.addEventListener('click', function (event) {
      var tab = event.target && event.target.closest ? event.target.closest('[data-openastro-vedic-tab]') : null;
      if (tab) {
        event.preventDefault();
        activateVedicResultTab(tab, false);
        return;
      }

      var button = event.target && event.target.closest ? event.target.closest('[data-openastro-print-chart="1"]') : null;
      if (button) {
        event.preventDefault();
        printChartCard(button.closest('.openastro-chart-card'));
      }
    });

    output.addEventListener('keydown', function (event) {
      var tab = event.target && event.target.closest ? event.target.closest('[data-openastro-vedic-tab]') : null;
      if (!tab || ['ArrowLeft', 'ArrowRight', 'Home', 'End'].indexOf(event.key) === -1) return;
      event.preventDefault();
      var report = tab.closest('.openastro-vedic-report');
      var tabs = Array.prototype.slice.call(report.querySelectorAll('[data-openastro-vedic-tab]'));
      var index = tabs.indexOf(tab);
      var nextIndex = event.key === 'Home'
        ? 0
        : (event.key === 'End' ? tabs.length - 1 : (index + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length);
      activateVedicResultTab(tabs[nextIndex], true);
    });
  }

  function mount(container) {
    var mode = container.getAttribute('data-mode') || 'chart-report';
    var cfg = getCfg();
    var lang = resolveLanguage(container.getAttribute('data-language') || cfg.language || 'auto');
    var includeInterpretation = container.getAttribute('data-include-interpretation') !== '0';
    var interpretationStyle = container.getAttribute('data-interpretation-style') === 'poetic' ? 'poetic' : 'improved';
    var simpleMode = isSimpleSignMode(mode);
    var personalizedHoroscopeMode = isPersonalizedHoroscopeMode(mode);

    container.classList.add('wp-block-astrology-suite-widget');
    setSuiteVariable(container, '--openastro-bg', cfg.style.transparentBackground ? 'transparent' : cfg.style.backgroundColor);
    setSuiteVariable(container, '--openastro-text', cfg.style.textColor);
    setSuiteVariable(container, '--openastro-input-radius', cfg.style.inputBorderRadius + 'px');
    setSuiteVariable(container, '--openastro-input-border-color', cfg.style.inputBorderColor);
    setSuiteVariable(container, '--openastro-input-bg', cfg.style.inputBgColor);
    setSuiteVariable(container, '--openastro-input-text', cfg.style.inputTextColor);
    setSuiteVariable(container, '--openastro-input-placeholder', cfg.style.inputPlaceholderColor);
    setSuiteVariable(container, '--openastro-history-bg', cfg.style.historyBgColor);
    setSuiteVariable(container, '--openastro-history-border', cfg.style.historyBorderColor);
    setSuiteVariable(container, '--openastro-history-text', cfg.style.historyTextColor);
    setSuiteVariable(container, '--openastro-button-radius', cfg.style.buttonBorderRadius + 'px');
    setSuiteVariable(container, '--openastro-button-bg', cfg.style.buttonBgColor);
    setSuiteVariable(container, '--openastro-button-text', cfg.style.buttonTextColor);
    setSuiteVariable(container, '--openastro-button-border', cfg.style.buttonBorderColor);
    setSuiteVariable(container, '--openastro-label-color', cfg.style.labelColor);
    setSuiteVariable(container, '--openastro-label-weight', cfg.style.labelWeight);
    setSuiteVariable(container, '--openastro-focus-ring', cfg.style.focusRingColor);
    setSuiteVariable(container, '--openastro-astro-font-size', cfg.style.astroFontSize + 'px');
    setSuiteVariable(container, '--openastro-astro-font-color', cfg.style.astroFontColor);
    setSuiteVariable(container, '--openastro-modal-bg', cfg.style.modalBgColor);
    setSuiteVariable(container, '--openastro-modal-text', cfg.style.modalTextColor);
    setSuiteVariable(container, '--openastro-modal-border', cfg.style.modalBorderColor);
    setSuiteVariable(container, '--openastro-modal-accent', cfg.style.modalAccentColor);
    setSuiteVariable(container, '--openastro-font-h2', cfg.typography.h2 + 'px');
    setSuiteVariable(container, '--openastro-font-h3', cfg.typography.h3 + 'px');
    setSuiteVariable(container, '--openastro-font-h4', cfg.typography.h4 + 'px');
    setSuiteVariable(container, '--openastro-font-body', cfg.typography.body + 'px');
    setSuiteVariable(container, '--openastro-font-table', cfg.typography.table + 'px');
    setSuiteVariable(container, '--openastro-font-input', cfg.typography.input + 'px');
    setSuiteVariable(container, '--openastro-font-button', cfg.typography.button + 'px');
    setSuiteVariable(container, '--openastro-font-meta', cfg.typography.meta + 'px');
    setSuiteVariable(container, '--openastro-ephemeris-table-bg', cfg.ephemerisStyle.tableBgColor);
    setSuiteVariable(container, '--openastro-ephemeris-table-text', cfg.ephemerisStyle.tableTextColor);
    setSuiteVariable(container, '--openastro-ephemeris-table-border', cfg.ephemerisStyle.tableBorderColor);
    setSuiteVariable(container, '--openastro-ephemeris-header-bg', cfg.ephemerisStyle.headerBgColor);
    setSuiteVariable(container, '--openastro-ephemeris-header-text', cfg.ephemerisStyle.headerTextColor);
    setSuiteVariable(container, '--openastro-ephemeris-badge-bg', cfg.ephemerisStyle.badgeBgColor);
    setSuiteVariable(container, '--openastro-ephemeris-badge-text', cfg.ephemerisStyle.badgeTextColor);
    setSuiteVariable(container, '--openastro-ephemeris-today-bg', cfg.ephemerisStyle.todayBgColor);
    setSuiteVariable(container, '--openastro-ephemeris-today-accent', cfg.ephemerisStyle.todayAccentColor);
    setSuiteVariable(container, '--openastro-ephemeris-retrograde', cfg.ephemerisStyle.retrogradeColor);
    setSuiteVariable(container, '--openastro-ephemeris-ingress', cfg.ephemerisStyle.ingressColor);
    setSuiteVariable(container, '--openastro-ephemeris-station', cfg.ephemerisStyle.stationColor);
    setSuiteVariable(container, '--openastro-horoscope-card-bg', cfg.horoscopeStyle.cardBgColor);
    setSuiteVariable(container, '--openastro-horoscope-card-text', cfg.horoscopeStyle.cardTextColor);
    setSuiteVariable(container, '--openastro-horoscope-card-border', cfg.horoscopeStyle.cardBorderColor);
    setSuiteVariable(container, '--openastro-horoscope-theme-bg', cfg.horoscopeStyle.themeBgColor);
    setSuiteVariable(container, '--openastro-horoscope-theme-text', cfg.horoscopeStyle.themeTextColor);
    setSuiteVariable(container, '--openastro-horoscope-chip-bg', cfg.horoscopeStyle.chipBgColor);
    setSuiteVariable(container, '--openastro-horoscope-chip-text', cfg.horoscopeStyle.chipTextColor);
    setSuiteVariable(container, '--openastro-horoscope-chip-border', cfg.horoscopeStyle.chipBorderColor);
    setSuiteVariable(container, '--openastro-horoscope-tile-bg', cfg.horoscopeStyle.tileBgColor);
    setSuiteVariable(container, '--openastro-horoscope-tile-text', cfg.horoscopeStyle.tileTextColor);
    setSuiteVariable(container, '--openastro-horoscope-tile-border', cfg.horoscopeStyle.tileBorderColor);
    setSuiteVariable(container, '--openastro-horoscope-accent', cfg.horoscopeStyle.accentColor);
    setSuiteVariable(container, '--openastro-vedic-surface-bg', cfg.vedicStyle.surfaceBgColor);
    setSuiteVariable(container, '--openastro-vedic-surface-text', cfg.vedicStyle.surfaceTextColor);
    setSuiteVariable(container, '--openastro-vedic-surface-border', cfg.vedicStyle.surfaceBorderColor);
    setSuiteVariable(container, '--openastro-vedic-accent', cfg.vedicStyle.accentColor);
    setSuiteVariable(container, '--openastro-vedic-tile-bg', cfg.vedicStyle.tileBgColor);
    setSuiteVariable(container, '--openastro-vedic-tile-text', cfg.vedicStyle.tileTextColor);
    setSuiteVariable(container, '--openastro-vedic-tile-border', cfg.vedicStyle.tileBorderColor);
    setSuiteVariable(container, '--openastro-vedic-table-header-bg', cfg.vedicStyle.tableHeaderBgColor);
    setSuiteVariable(container, '--openastro-vedic-table-header-text', cfg.vedicStyle.tableHeaderTextColor);
    setSuiteVariable(container, '--openastro-vedic-badge-bg', cfg.vedicStyle.badgeBgColor);
    setSuiteVariable(container, '--openastro-vedic-badge-text', cfg.vedicStyle.badgeTextColor);
    setSuiteVariable(container, '--openastro-bot-shell-bg', cfg.botStyle.shellBgColor);
    setSuiteVariable(container, '--openastro-bot-header-bg', cfg.botStyle.headerBgColor);
    setSuiteVariable(container, '--openastro-bot-screen-bg', cfg.botStyle.screenBgColor);
    setSuiteVariable(container, '--openastro-bot-accent', cfg.botStyle.accentColor);
    setSuiteVariable(container, '--openastro-bot-user-bubble-bg', cfg.botStyle.userBubbleBgColor);
    setSuiteVariable(container, '--openastro-bot-user-bubble-text', cfg.botStyle.userBubbleTextColor);
    setSuiteVariable(container, '--openastro-bot-assistant-bubble-bg', cfg.botStyle.assistantBubbleBgColor);
    setSuiteVariable(container, '--openastro-bot-assistant-bubble-text', cfg.botStyle.assistantBubbleTextColor);
    if (cfg.style.showBorder === false) {
      container.classList.add('astrology-suite-no-border');
      container.classList.add('openastro-no-border');
    } else {
      container.classList.remove('astrology-suite-no-border');
      container.classList.remove('openastro-no-border');
    }

    if (!cfg.permissions.canCalculate) {
      var accessNote = create('section', { class: 'openastro-card openastro-access-note' });
      accessNote.appendChild(create('h3', null, t('Calculation Access Required', lang)));
      accessNote.appendChild(create('p', null, cfg.permissions.calculationMessage));
      container.appendChild(accessNote);
      return;
    }

    if (isEphemerisMode(mode)) {
      mountEphemeris(container, cfg);
      return;
    }

    if (isAstrologyBotMode(mode)) {
      mountAstrologyBot(container, cfg, lang);
      return;
    }

    if (isDailyHoroscopeMode(mode)) {
      mountDailyHoroscope(container, cfg, lang);
      return;
    }

    if (isVedicMode(mode)) {
      mountVedic(container, cfg, mode);
      return;
    }

    var form = create('form', { class: 'openastro-form' });
    var historySelect = null;
    var showHistory = cfg.style.showHistory !== false;
    if (showHistory) {
      var formHeader = create('div', { class: 'openastro-form-header' });
      var historyWrap = create('div', { class: 'openastro-load-recent-wrap' });
      var historyPanel = create('div', { class: 'openastro-load-recent-panel' });
      var historyId = 'openastro-history-select-' + Math.random().toString(36).slice(2, 10);
      var historyLabel = create('label', { class: 'openastro-history-label openastro-load-recent-label', for: historyId }, t('Past inputs', lang));
      historySelect = create('select', { id: historyId, class: 'openastro-history-select openastro-load-recent-select', 'aria-label': t('Load recent past inputs', lang) });
      var historyDefaultOption = create('option', { value: '' }, t('Past inputs...', lang));
      historySelect.appendChild(historyDefaultOption);
      historyPanel.appendChild(historyLabel);
      historyPanel.appendChild(historySelect);
      historyWrap.appendChild(historyPanel);
      formHeader.appendChild(historyWrap);
      form.appendChild(formHeader);
    }
    var name = create('input', { type: 'text', class: 'openastro-input', placeholder: t('Name (optional)', lang), 'aria-label': t('Name (optional)', lang) });
    var date = create('input', { type: 'date', class: 'openastro-input', 'aria-label': t('Birth date', lang) });
    var targetDate = create('input', { type: 'date', class: 'openastro-input', 'aria-label': t('Horoscope date', lang) });
    var timeControl = createTimeControls(lang);
    var timeUnknownWrap = create('label', { class: 'openastro-inline openastro-time-unknown-wrap' });
    var timeUnknown = create('input', { type: 'checkbox' });
    timeUnknownWrap.appendChild(timeUnknown);
    timeUnknownWrap.appendChild(document.createTextNode(' ' + t("I don't know my birth time", lang)));
    var cityId = 'openastro-city-input-' + Math.random().toString(36).slice(2, 10);
    var cityResultsId = 'openastro-city-results-' + Math.random().toString(36).slice(2, 10);
    var cityMessageId = 'openastro-city-message-' + Math.random().toString(36).slice(2, 10);
    var city = create('input', {
      id: cityId,
      type: 'text',
      class: 'openastro-input openastro-city-input',
      placeholder: t('City or city, country code', lang),
      'aria-label': t('City or city, country code', lang),
      role: 'combobox',
      'aria-autocomplete': 'list',
      'aria-haspopup': 'listbox',
      'aria-controls': cityResultsId,
      'aria-describedby': cityMessageId,
      'aria-expanded': 'false'
    });
    var cityWrap = create('div', { class: 'openastro-city-field' });
    var cityLoading = create('p', { id: cityMessageId, class: 'openastro-city-loading', 'aria-live': 'polite' }, '');
    var cityResults = create('div', { id: cityResultsId, class: 'openastro-results', role: 'listbox', 'aria-label': t('City suggestions', lang) });
    var includeMinorWrap = create('label', { class: 'openastro-inline' });
    var includeMinor = create('input', { type: 'checkbox', checked: 'checked' });
    includeMinorWrap.appendChild(includeMinor);
    includeMinorWrap.appendChild(document.createTextNode(' ' + t('Include minor aspects', lang)));

    var submit = create('button', { type: 'submit', class: 'openastro-btn' }, t('Generate', lang));
    var status = create('p', { class: 'openastro-status' }, '');
    var output = create('div', { class: 'openastro-output' });
    var modalRoot = create('div', { class: 'openastro-aspect-modal-root', hidden: 'hidden' });
    var currentAspectModalStore = [];

    form.appendChild(name);
    form.appendChild(date);
    if (personalizedHoroscopeMode) {
      targetDate.value = dateTodayValue();
      form.appendChild(targetDate);
    }
    form.appendChild(timeControl.wrap);
    if (!simpleMode) {
      form.appendChild(timeUnknownWrap);
    }
    cityWrap.appendChild(city);
    cityWrap.appendChild(cityLoading);
    cityWrap.appendChild(cityResults);
    form.appendChild(cityWrap);
    if (!simpleMode && !personalizedHoroscopeMode) {
      form.appendChild(includeMinorWrap);
    }
    form.appendChild(submit);
    form.appendChild(status);

    container.appendChild(form);
    container.appendChild(output);
    container.appendChild(modalRoot);

    var cityTimer = null;
    var cityRequestId = 0;
    var citySuggestions = [];
    var activeCityIndex = -1;
    var selectedCity = null;
    var historyEntries = showHistory ? getHistory() : [];

    function closeAspectModal() {
      modalRoot.setAttribute('hidden', 'hidden');
      modalRoot.innerHTML = '';
    }

    function openAspectModal(item) {
      if (!item) {
        return;
      }

      modalRoot.innerHTML = '';
      modalRoot.removeAttribute('hidden');

      var modal = create('section', { class: 'openastro-aspect-modal', role: 'dialog', 'aria-modal': 'true', 'aria-label': item.title || t('Interpretation', lang) });
      var header = create('div', { class: 'openastro-aspect-modal-header' });
      var titleWrap = create('div', { class: 'openastro-aspect-modal-title-wrap' });
      titleWrap.appendChild(create('h3', null, item.title || t('Interpretation', lang)));
      titleWrap.appendChild(create('p', { class: 'openastro-aspect-modal-meta' }, item.meta || ''));
      var close = create('button', { type: 'button', class: 'openastro-aspect-modal-close' }, t('Close', lang));
      close.addEventListener('click', closeAspectModal);
      header.appendChild(titleWrap);
      header.appendChild(close);
      modal.appendChild(header);
      modal.appendChild(create('h4', null, item.heading || item.title || t('Interpretation', lang)));
      modal.appendChild(create('p', { class: 'openastro-aspect-modal-body' }, item.body || t('Interpretation was not returned for this aspect.', lang)));
      modalRoot.appendChild(modal);
      close.focus();
    }

    modalRoot.addEventListener('click', function (event) {
      if (event.target === modalRoot) {
        closeAspectModal();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modalRoot.hasAttribute('hidden')) {
        closeAspectModal();
      }
    });

    function timeKnownSelected() {
      return simpleMode || !timeUnknown.checked;
    }

    function syncTimeKnownState() {
      var timeKnown = timeKnownSelected();
      timeControl.setDisabled(!timeKnown);
      if (!timeKnown) {
        timeControl.setValue('');
      }
    }

    timeUnknown.addEventListener('change', syncTimeKnownState);
    syncTimeKnownState();

    function setCityExpanded(expanded) {
      var isExpanded = expanded && citySuggestions.length;
      city.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
      cityWrap.classList.toggle('is-open', !!isExpanded);
      if (!expanded) {
        city.removeAttribute('aria-activedescendant');
      }
    }

    function setCityMessage(message) {
      cityLoading.textContent = message || '';
    }

    function clearCityResults() {
      citySuggestions = [];
      activeCityIndex = -1;
      cityResults.innerHTML = '';
      setCityExpanded(false);
    }

    function setActiveCityIndex(index) {
      var buttons = cityResults.querySelectorAll('.openastro-result-btn');
      if (!citySuggestions.length || !buttons.length) {
        activeCityIndex = -1;
        city.removeAttribute('aria-activedescendant');
        return;
      }

      activeCityIndex = (index + citySuggestions.length) % citySuggestions.length;
      Array.prototype.forEach.call(buttons, function (button, idx) {
        var isActive = idx === activeCityIndex;
        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-selected', isActive ? 'true' : 'false');
        if (isActive) {
          city.setAttribute('aria-activedescendant', button.id);
          button.scrollIntoView({ block: 'nearest' });
        }
      });
    }

    function chooseCity(item) {
      cityRequestId += 1;
      selectedCity = item;
      city.value = cityValueLabel(item);
      setCityMessage(cityTimezoneLabel(item)
        ? fmt('Selected {city} - {timezone}.', { city: cityValueLabel(item), timezone: cityTimezoneLabel(item) }, lang)
        : fmt('Selected {city}.', { city: cityValueLabel(item) }, lang));
      clearCityResults();
    }

    function renderCitySuggestions(items, query) {
      cityResults.innerHTML = '';
      citySuggestions = items;
      activeCityIndex = -1;

      items.forEach(function (item, idx) {
        var button = create('button', {
          id: cityResultsId + '-option-' + idx,
          type: 'button',
          class: 'openastro-result-btn',
          role: 'option',
          'aria-selected': 'false',
          'aria-label': citySuggestionLabel(item)
        });
        var main = create('span', { class: 'openastro-result-main' });
        var primary = create('span', { class: 'openastro-result-primary' });
        var countryCode = cityCountryCode(item);
        primary.appendChild(create('span', { class: 'openastro-result-name' }, item.name || t('Unknown city', lang)));
        if (countryCode) {
          primary.appendChild(create('span', { class: 'openastro-result-country' }, countryCode));
        }
        main.appendChild(primary);

        var metaParts = cityMetaParts(item);

        button.appendChild(main);
        if (metaParts.length) {
          var meta = create('span', { class: 'openastro-result-meta' });
          metaParts.forEach(function (part) {
            meta.appendChild(create('span', { class: 'openastro-result-meta-item' }, part));
          });
          button.appendChild(meta);
        }
        button.addEventListener('click', function () {
          chooseCity(item);
        });
        cityResults.appendChild(button);
      });

      setCityExpanded(true);
      setActiveCityIndex(0);
      setCityMessage(fmt(items.length === 1 ? '{count} match for "{query}". Use arrow keys or click a city.' : '{count} matches for "{query}". Use arrow keys or click a city.', { count: items.length, query: query }, lang));
    }

    function refreshHistorySelect() {
      if (!historySelect) {
        return;
      }
      historySelect.innerHTML = '';
      historySelect.appendChild(create('option', { value: '' }, t('Past inputs...', lang)));
      historyEntries.forEach(function (entry, idx) {
        var option = create('option', { value: String(idx) }, historyOptionLabel(entry, lang));
        historySelect.appendChild(option);
      });
    }

    function applyHistoryEntry(entry) {
      if (!entry) return;
      name.value = entry.name || '';
      date.value = toDateValue(entry);
      if (!simpleMode) {
        timeUnknown.checked = entry.time_known === false;
      }
      timeControl.setValue(toTimeValue(entry));
      syncTimeKnownState();
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
      cityRequestId += 1;
      clearCityResults();
      setCityMessage('');
      status.textContent = t('Loaded saved input.', lang);
    }

    refreshHistorySelect();
    if (historySelect) {
      historySelect.addEventListener('change', function () {
        var idx = Number(historySelect.value);
        if (!historySelect.value || Number.isNaN(idx)) {
          return;
        }
        applyHistoryEntry(historyEntries[idx]);
      });
    }

    function saveHistoryEntry(includeMinorValue, payload, cityItem) {
      if (!showHistory) {
        return;
      }
      historyEntries = upsertHistory(makeHistoryEntry(mode, payload, cityItem, includeMinorValue));
      refreshHistorySelect();
    }

    city.addEventListener('input', function () {
      var parsedSearch = parseCitySearchInput(city.value);
      selectedCity = null;
      clearCityResults();
      setCityMessage('');
      if (cityTimer) {
        clearTimeout(cityTimer);
      }
      cityRequestId += 1;
      var requestId = cityRequestId;
      if (parsedSearch.query.length < 2) {
        if (parsedSearch.query.length > 0) {
          setCityMessage(t('Type at least 2 characters to search cities.', lang));
        }
        return;
      }

      setCityMessage(t('Searching cities...', lang));
      cityTimer = setTimeout(function () {
        var path = '/geo/search?q=' + encodeURIComponent(parsedSearch.query) + '&limit=8';
        if (parsedSearch.country) {
          path += '&country=' + encodeURIComponent(parsedSearch.country);
        }
        api(path, { method: 'GET' })
          .then(function (result) {
            if (requestId !== cityRequestId) {
              return;
            }
            clearCityResults();
            setCityMessage('');
            if (!result.ok || !result.body || !Array.isArray(result.body.results)) {
              setCityMessage(t('Could not load city suggestions.', lang));
              return;
            }
            if (result.body.results.length === 0) {
              setCityMessage(t('No cities found. Try another query.', lang));
              return;
            }

            renderCitySuggestions(sortCitySuggestions(result.body.results, parsedSearch.query), parsedSearch.query);
          })
          .catch(function () {
            if (requestId !== cityRequestId) {
              return;
            }
            clearCityResults();
            setCityMessage(t('Could not load city suggestions.', lang));
          });
      }, 300);
    });

    city.addEventListener('keydown', function (event) {
      if (!citySuggestions.length) {
        return;
      }
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        setActiveCityIndex(activeCityIndex < 0 ? 0 : activeCityIndex + 1);
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        setActiveCityIndex(activeCityIndex < 0 ? citySuggestions.length - 1 : activeCityIndex - 1);
      } else if (event.key === 'Enter' && activeCityIndex >= 0) {
        event.preventDefault();
        chooseCity(citySuggestions[activeCityIndex]);
      } else if (event.key === 'Escape') {
        event.preventDefault();
        clearCityResults();
        setCityMessage('');
      }
    });

    city.addEventListener('blur', function () {
      window.setTimeout(function () {
        if (!cityResults.contains(document.activeElement)) {
          clearCityResults();
        }
      }, 120);
    });

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var timeValue = timeControl.value();
      var timeKnown = timeKnownSelected();
      if (!date.value || !selectedCity) {
        status.textContent = t('Date and a city from suggestions are required.', lang);
        return;
      }
      if (timeKnown && !timeValue) {
        status.textContent = t('Birth time is required, or choose unknown time.', lang);
        return;
      }

      var d = date.value.split('-');
      var payload = {
        name: name.value || '',
        year: Number(d[0]),
        month: Number(d[1]),
        day: Number(d[2]),
        city: selectedCity.name,
        lat: selectedCity.lat,
        lng: selectedCity.lng,
        tz_str: selectedCity.timezone,
        language: lang
      };

      if (!simpleMode) {
        payload.time_known = timeKnown;
      }

      if (timeKnown) {
        var tParts = timeValue.split(':');
        payload.hour = Number(tParts[0]);
        payload.minute = Number(tParts[1]);
      }

      submit.disabled = true;
      status.textContent = t('Generating...', lang);
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
              status.textContent = (result.body && result.body.message) || t('Could not generate simple sign.', lang);
              return;
            }

            output.innerHTML = simpleSignHtml(result.body, includeInterpretation, lang);
            status.textContent = t('Done.', lang);
            saveHistoryEntry(false, payload, selectedCity);
          })
          .catch(function () {
            status.textContent = t('Unexpected error while generating data.', lang);
          })
          .finally(function () {
            submit.disabled = false;
          });

        return;
      }

      if (personalizedHoroscopeMode) {
        var personalPayload = {
          birth: {
            year: payload.year,
            month: payload.month,
            day: payload.day,
            city: payload.city,
            lat: payload.lat,
            lng: payload.lng,
            tz_str: payload.tz_str,
            time_known: payload.time_known !== false
          },
          date: targetDate.value || 'today',
          tz_str: payload.tz_str,
	          include_interpretation_blocks: includeInterpretation !== false
	        };
        personalPayload.language = lang;
        if (payload.time_known !== false) {
          personalPayload.birth.hour = payload.hour;
          personalPayload.birth.minute = payload.minute;
        }

        api('/western/personalized-horoscope', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(personalPayload)
        })
          .then(function (result) {
            if (!result.ok || !result.body) {
              status.textContent = (result.body && result.body.message) || t('Could not generate personalized horoscope.', lang);
              return;
            }

            output.innerHTML = dailyHoroscopeHtml(result.body, lang, cfg);
            status.textContent = t('Done.', lang);
            saveHistoryEntry(false, payload, selectedCity);
            window.dispatchEvent(new CustomEvent('openastro:personalized-horoscope:generated', {
              detail: {
                request: personalPayload,
                response: result.body
              }
            }));
          })
          .catch(function () {
            status.textContent = t('Unexpected error while generating data.', lang);
          })
          .finally(function () {
            submit.disabled = false;
          });

        return;
      }

      payload.include_minor_aspects = includeMinor.checked;

      function postNatalCalculate() {
        return api('/natal/calculate', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      }

      function postChartSvg() {
        return api('/natal/chart-svg', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      }

      function renderGeneration(natalBody, svg, message) {
        currentAspectModalStore = [];
        var detail = {
          request: payload,
          response: natalBody || null,
          chartSvg: svg || null
        };

        window.dispatchEvent(new CustomEvent('openastro:natal:generated', { detail: detail }));

        if (mode === 'chart-only') {
          output.innerHTML = chartSectionHtml(svg, cfg.report, lang);
        } else if (mode === 'report-only') {
          output.innerHTML = reportHtml(natalBody, '', mode, cfg.report, payload, selectedCity, lang, currentAspectModalStore);
        } else {
          output.innerHTML = reportHtml(natalBody, svg, mode, cfg.report, payload, selectedCity, lang, currentAspectModalStore);
        }

        status.textContent = message || t('Done.', lang);
        saveHistoryEntry(includeMinor.checked, payload, selectedCity);
      }

      if (mode === 'chart-only') {
        postChartSvg()
          .then(function (chart) {
            if (!chart.ok || !chart.body || !chart.body.svg) {
              status.textContent = (chart.body && chart.body.message) || t('Could not generate natal chart.', lang);
              return;
            }

            renderGeneration(null, chart.body.svg, t('Done.', lang));
          })
          .catch(function () {
            status.textContent = t('Unexpected error while generating data.', lang);
          })
          .finally(function () {
            submit.disabled = false;
          });

        return;
      }

      if (mode === 'report-only') {
        postNatalCalculate()
          .then(function (natal) {
            if (!natal.ok || !natal.body) {
              status.textContent = (natal.body && natal.body.message) || t('Could not generate natal data.', lang);
              return;
            }

            renderGeneration(natal.body, '', t('Done.', lang));
          })
          .catch(function () {
            status.textContent = t('Unexpected error while generating data.', lang);
          })
          .finally(function () {
            submit.disabled = false;
          });

        return;
      }

      Promise.all([postNatalCalculate(), postChartSvg()])
        .then(function (result) {
          var natal = result[0];
          var chart = result[1];

          if (!natal.ok || !natal.body) {
            status.textContent = (natal.body && natal.body.message) || t('Could not generate natal data.', lang);
            return;
          }

          var svg = chart.ok && chart.body && chart.body.svg ? chart.body.svg : '';
          renderGeneration(natal.body, svg, svg ? t('Done.', lang) : t('Done. Chart unavailable.', lang));
        })
        .catch(function () {
          status.textContent = t('Unexpected error while generating data.', lang);
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

      var aspectButton = target.closest('[data-openastro-aspect-modal]');
      if (aspectButton) {
        event.preventDefault();
        var idx = Number(aspectButton.getAttribute('data-openastro-aspect-modal'));
        openAspectModal(currentAspectModalStore[idx]);
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
    var nodes = document.querySelectorAll('[data-astrology-suite-widget="1"], [data-openastro-widget="1"]');
    nodes.forEach(mount);
  });
})();
