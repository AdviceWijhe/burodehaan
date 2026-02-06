# Header Systeem Documentatie

Dit thema heeft een flexibel header systeem met 4 verschillende layouts die je kunt kiezen via de WordPress Customizer.

## WordPress Customizer Instellingen

Ga naar **Weergave → Aanpassen → Header Instellingen** om de volgende opties in te stellen:

### 1. Header Layout
Kies uit 4 verschillende layouts:

#### Layout 1: Logo links + Menu rechts + Topbar
- Logo aan de linkerkant
- Navigatiemenu aan de rechterkant met dropdowns
- Zoekknop, secundaire button en contact button
- Optionele topbar met voordelen

**Gebaseerd op Figma:** https://www.figma.com/design/EnnctIvaE527aCXNcA9xjX/Standaard-website?node-id=2022-862

#### Layout 2: Logo + Menu links + Button rechts + Topbar
- Logo en navigatiemenu aan de linkerkant
- Contact button aan de rechterkant
- Optionele topbar met contactinformatie (telefoon, email, taal selector)

**Gebaseerd op Figma:** https://www.figma.com/design/EnnctIvaE527aCXNcA9xjX/Standaard-website?node-id=2022-866

#### Layout 3: Logo links + Buttons rechts + Menu gecentreerd onder
- Bovenste rij: Logo links, buttons rechts (zoeken, secundaire button, contact)
- Onderste rij: Gecentreerd navigatiemenu

**Gebaseerd op Figma:** https://www.figma.com/design/EnnctIvaE527aCXNcA9xjX/Standaard-website?node-id=2022-868

#### Layout 4: Logo links + Menu gecentreerd + Buttons rechts
- 3-kolommen grid layout
- Logo links, navigatie gecentreerd, buttons rechts
- Alles op één rij

**Gebaseerd op Figma:** https://www.figma.com/design/EnnctIvaE527aCXNcA9xjX/Standaard-website?node-id=2020-646

### 2. Topbar Activeren
Schakel de topbar in of uit. De topbar verschijnt boven de hoofdnavigatie.

### 3. Container Breedte
Kies tussen:
- **Volledige breedte**: Header strekt zich uit over de volledige breedte van de pagina
- **Binnen container**: Header blijft binnen de container breedte

### 4. Topbar Stijl
Kies welke informatie in de topbar wordt weergegeven:
- **Voordelen (Layout 1)**: Toont voordelen met checkmarks aan de linkerkant, menu items aan de rechterkant
- **Contact informatie (Layout 2)**: Toont telefoon en email links, menu items en taal selector

## ACF Options Velden

Om de header volledig te configureren, maak de volgende ACF velden aan in **Options Pages**:

### Header Buttons
- `header_secondary_button` (Link) - Secundaire button link en tekst
- `header_contact_button` (Link) - Contact button link en tekst

### Topbar Voordelen (Layout 1)
- `topbar_voordelen` (Repeater)
  - `tekst` (Text) - Voordeel tekst

### Topbar Contact Info (Layout 2)
- `topbar_phone` (Text) - Telefoonnummer
- `topbar_email` (Email) - E-mailadres

### Mobile Menu CTA
- `header_cta` (Group)
  - `link` (Link)
  - `afbeelding` (Image)
  - `titel` (Text)
  - `subtitel` (Text)

## WordPress Menu's

Registreer de volgende menu's via **Weergave → Menu's**:

1. **Primary Menu** - Hoofdnavigatie
2. **Topbar Menu** - Menu items in de topbar (rechts)

## Bestanden Structuur

```
template-parts/headers/
├── topbar.php           # Topbar template (voordelen of contact info)
├── nav-layout-1.php     # Navigation Layout 1
├── nav-layout-2.php     # Navigation Layout 2
├── nav-layout-3.php     # Navigation Layout 3
├── nav-layout-4.php     # Navigation Layout 4
└── README.md            # Deze documentatie
```

## Styling

Alle headers gebruiken Tailwind CSS classes voor styling. De kleuren en fonts zijn afgestemd op de Figma designs:

- Achtergrondkleur header: `bg-white`
- Achtergrondkleur topbar: `bg-[rgba(216,214,212,0.4)]`
- Tekstkleur: `text-[#131611]`
- Accent kleur (Contact button): `bg-[#FF5822]`
- Secundaire button: `bg-[rgba(19,22,17,0.4)]` met backdrop blur
- Font: PP Neue Montreal (Medium, 16px voor labels, 14px voor topbar)

## Mobile Responsiveness

Alle layouts hebben een geïntegreerde mobile menu functionaliteit:
- Hamburger menu button op mobiel
- Slide-out menu met drilldown navigatie voor submenus
- Automatisch verbergen bij grotere schermen (xl breakpoint: 1280px)

## Testen

Om de verschillende layouts te testen:

1. Ga naar **Weergave → Aanpassen → Header Instellingen**
2. Selecteer een layout uit de dropdown
3. Schakel de topbar in/uit
4. Kies container breedte
5. Klik op **Publiceren** om de wijzigingen op te slaan
6. Bekijk de website om het resultaat te zien

## Tips

- Voor Layout 1 werkt de topbar stijl "Voordelen" het beste
- Voor Layout 2 werkt de topbar stijl "Contact informatie" het beste
- Layout 3 en 4 hebben geen standaard topbar ontwerp in de Figma maar kunnen wel de topbar gebruiken
- Gebruik de "Binnen container" optie voor een meer gecentreerde look
- Gebruik "Volledige breedte" voor een edge-to-edge design

## Support

Als je vragen hebt over dit header systeem, neem dan contact op met de ontwikkelaar.
