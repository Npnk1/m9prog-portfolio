# Portfolio Nikita Pasenkov – M9PROG

Persoonlijke portfolio-website, gebouwd als eigen WordPress-theme (`np-portfolio`).
Lokale ontwikkeling draait in Docker; de front-end wordt gebouwd met Bootstrap, Sass, npm en Webpack.

## Benodigd

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (met Docker Compose)
- [Node.js](https://nodejs.org/) 20 of nieuwer, alleen nodig om CSS en JavaScript opnieuw te bouwen
- Git

## Lokaal starten

```bash
# 1. Instellingen aanmaken en eigen wachtwoorden invullen
cp .env.example .env

# 2. WordPress, MySQL en phpMyAdmin starten
docker compose up -d

# 3. Eenmalig: WordPress installeren en vullen met pagina's, projecten en menu
docker compose run --rm wpcli sh /seed/seed.sh
```

| Wat           | Adres                           |
|---------------|---------------------------------|
| Website       | http://localhost:8080           |
| Dashboard     | http://localhost:8080/wp-admin  |
| phpMyAdmin    | http://localhost:8081           |

Inloggen met `WP_ADMIN_USER` en `WP_ADMIN_PASSWORD` uit `.env`.

Stoppen: `docker compose down`. Alles wissen, inclusief de database: `docker compose down -v`.

> Is poort 8080 of 8081 al in gebruik? Pas de poort aan in `docker-compose.yml` en in `WP_URL` in `.env`.

## Front-end bouwen

```bash
cd wp-content/themes/np-portfolio
npm ci            # dependencies installeren (exacte versies uit package-lock.json)
npm run build     # productieversie naar dist/
npm run dev       # ontwikkelen: bouwt opnieuw bij elke wijziging
```

`src/` bevat de bronbestanden (Sass en JavaScript), `dist/` het resultaat. `dist/` staat bewust in Git,
zodat het theme na het clonen direct werkt, ook zonder Node. Na een wijziging in `src/` altijd opnieuw bouwen.

## Database-dump maken

Werkt in PowerShell, Git Bash en macOS/Linux:

```bash
docker compose exec db sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysqldump -uroot --no-tablespaces "$MYSQL_DATABASE" > /dump/database.sql'
```

Het bestand komt in `database/database.sql`. Inpakken als zip:

```powershell
# Windows (PowerShell)
Compress-Archive -Path database/database.sql -DestinationPath database/database.zip -Force
```

```bash
# macOS / Linux
zip -j database/database.zip database/database.sql
```

Een dump terugzetten:

```bash
docker compose exec db sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot "$MYSQL_DATABASE" < /dump/database.sql'
```

Dumps staan in `.gitignore`; ze worden apart ingeleverd.

## Structuur

```
.
├── docker-compose.yml          WordPress, MySQL, phpMyAdmin en WP-CLI
├── .env.example                voorbeeld van de instellingen (echte .env staat niet in Git)
├── seed/                       script, teksten en afbeeldingen om de site te vullen
├── database/                   hier komt de database-dump
└── wp-content/themes/np-portfolio/
    ├── style.css               theme-header
    ├── functions.php           laadt de onderdelen uit inc/
    ├── inc/
    │   ├── setup.php           theme supports, menu, Bootstrap-klassen in het menu
    │   ├── enqueue.php         CSS en JS uit dist/ laden
    │   ├── template-tags.php   hulpfuncties voor templates
    │   ├── cpt-project.php     Custom Post Type "project"
    │   ├── project-meta.php    velden rol, technieken, resultaat, jaar, link
    │   └── contact.php         contactformulier verwerken
    ├── header.php / footer.php
    ├── front-page.php          homepagina
    ├── page-over-mij.php       pagina "Over mij" (page-{slug}.php)
    ├── page-contact.php        pagina "Contact" met formulier
    ├── archive-project.php     projectoverzicht (/projecten/)
    ├── single-project.php      projectdetail (/projecten/<naam>/)
    ├── template-parts/project-row.php
    ├── page.php, single.php, index.php, 404.php
    ├── src/scss/ + src/js/     bronbestanden
    ├── dist/                   gebouwde CSS, JS en lettertypen
    ├── package.json
    └── webpack.config.js
```

## Technische keuzes

- **Eigen theme, geen page builder.** De templatehiërarchie bepaalt welk bestand WordPress gebruikt: `front-page.php` voor de homepage, `page-over-mij.php` en `page-contact.php` op basis van de slug, `archive-project.php` en `single-project.php` voor het Custom Post Type.
- **Custom Post Type `project`** met een archief op `/projecten/`. De extra velden staan in een eigen meta box zonder plugin. Opslaan gebeurt met een nonce, een rechtencontrole en opschoning per veldtype.
- **Contactformulier zonder plugin.** Validatie gebeurt op de server, met nonce en een honeypot tegen spam. Elk bericht wordt opgeslagen onder *Berichten* in het dashboard en daarna gemaild. Lokaal is er geen mailserver, dus daar staan berichten alleen in het dashboard. Na versturen volgt een redirect (Post/Redirect/Get), zodat verversen het bericht niet dubbel verstuurt.
- **Bootstrap via Sass.** Alleen de onderdelen die de site gebruikt worden geïmporteerd. Kleuren en typografie zijn aangepast via Bootstrap-variabelen in `_tokens.scss`.
- **Lettertype zelf gehost** via npm (`@fontsource-variable/bricolage-grotesque`), zodat bezoekers geen verzoek naar Google sturen.
- **Webpack** bouwt Sass via `sass-loader` → `css-loader` → `MiniCssExtractPlugin` naar één CSS-bestand en minificeert het. `charset: false` voorkomt dat Sass een BOM midden in de CSS zet, waardoor de `:root`-variabelen van Bootstrap ongeldig zouden worden.
- **Geen geheimen in Git.** Wachtwoorden staan in `.env`.
- **Toegankelijkheid:** skip-link, zichtbare focus, labels bij alle velden, foutmeldingen gekoppeld met `aria-describedby`, voldoende kleurcontrast en animatie alleen als de gebruiker beweging toestaat.

## AI-gebruik

Zie [AI-LOG.md](AI-LOG.md).
