#!/bin/sh
# Vult een lege WordPress-installatie met de pagina's, projecten en het menu van het portfolio.
# Draait IN de wpcli-container:
#   docker compose run --rm wpcli sh /seed/seed.sh
set -e
cd /var/www/html

echo "Wachten tot WordPress klaar is..."
tries=0
until [ -f wp-config.php ]; do
  tries=$((tries + 1))
  if [ "$tries" -gt 60 ]; then
    echo "wp-config.php niet gevonden. Draait 'docker compose up -d'?"
    exit 1
  fi
  sleep 2
done

echo "Wachten op de database..."
tries=0
until php -r 'mysqli_report(MYSQLI_REPORT_OFF); $c = @mysqli_connect(getenv("WORDPRESS_DB_HOST"), getenv("WORDPRESS_DB_USER"), getenv("WORDPRESS_DB_PASSWORD"), getenv("WORDPRESS_DB_NAME")); exit($c ? 0 : 1);'; do
  tries=$((tries + 1))
  if [ "$tries" -gt 60 ]; then
    echo "Geen verbinding met de database. Controleer de gegevens in .env."
    exit 1
  fi
  sleep 2
done

if [ -z "$WP_ADMIN_PASSWORD" ]; then
  echo "WP_ADMIN_PASSWORD ontbreekt in .env."
  exit 1
fi

if ! wp core is-installed 2>/dev/null; then
  echo "WordPress installeren..."
  wp core install \
    --url="$WP_URL" \
    --title="Nikita Pasenkov" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
fi

if [ "$(wp option get np_seeded 2>/dev/null || true)" = "1" ]; then
  echo "De site is al gevuld. Niets gedaan."
  exit 0
fi

echo "Nederlandse taal installeren (lukt dit niet, dan gaat het script gewoon door)..."
wp language core install nl_NL --activate || echo "Taalpakket overgeslagen."

echo "Theme en instellingen..."
wp theme activate np-portfolio
wp option update blogdescription "Software developer in opleiding"
wp option update timezone_string "Europe/Amsterdam"
wp option update date_format "j F Y"
wp rewrite structure "/%postname%/"

# Standaardinhoud van WordPress verwijderen ("Hallo wereld" en "Voorbeeldpagina")
wp post delete 1 2 --force || true

echo "Pagina's aanmaken..."
HOME_ID=$(wp post create /seed/content/home.html --post_type=page --post_status=publish --post_title="Home" --post_name="home" --porcelain)
ABOUT_ID=$(wp post create /seed/content/over-mij.html --post_type=page --post_status=publish --post_title="Over mij" --post_name="over-mij" --post_excerpt="Derdejaars student Software Developer in Amsterdam, op zoek naar een stage in webdevelopment." --porcelain)
CONTACT_ID=$(wp post create /seed/content/contact.html --post_type=page --post_status=publish --post_title="Contact" --post_name="contact" --porcelain)

wp option update show_on_front page
wp option update page_on_front "$HOME_ID"

echo "Projecten aanmaken..."
# create_project <slug> <titel> <samenvatting> <rol> <technieken> <resultaat> <jaar> <datum>
create_project() {
  ID=$(wp post create "/seed/content/$1.html" \
    --post_type=project \
    --post_status=publish \
    --post_name="$1" \
    --post_title="$2" \
    --post_excerpt="$3" \
    --post_date="$8" \
    --porcelain)
  wp post meta update "$ID" _np_role "$4" >/dev/null
  wp post meta update "$ID" _np_tech "$5" >/dev/null
  wp post meta update "$ID" _np_result "$6" >/dev/null
  if [ -n "$7" ]; then
    wp post meta update "$ID" _np_year "$7" >/dev/null
  fi
  wp media import "/seed/images/$1.png" --post_id="$ID" --featured_image --title="$2" --alt="Afbeelding van project $2" >/dev/null
  echo "  - $2"
}

create_project "rijschool" "RijSchool" \
  "C#-applicatie voor een rijschool, gemaakt om objectgeoriënteerd programmeren te oefenen." \
  "Solo-developer (schoolopdracht)" \
  "C#, .NET, Git" \
  "Basis van de applicatie met eigen klassen, velden en properties, gebouwd en gestart met dotnet build en dotnet run." \
  "" "2026-03-01 10:00:00"

create_project "web2go" "Web2Go" \
  "Website voor een startend digitaal bureau, opgezet in drie talen." \
  "Ontwerp en front-end. Gestart in een team, daarna zelfstandig verder." \
  "Next.js, TypeScript, Tailwind CSS, ESLint" \
  "Engelse homepage af; lint, type-check en build slagen. Layout gecontroleerd op 1920, 1440, 1024, 768 en 390 pixels breed. Routes voor Engels, Nederlands en Spaans." \
  "2026" "2026-09-01 10:00:00"

create_project "arduino-web-serial" "Arduino in de browser" \
  "Gegevens van een Arduino live tonen in de browser met de Web Serial API." \
  "Solo (module IoT)" \
  "Arduino, C++, JavaScript, Web Serial API" \
  "Arduino-sketches en webpagina's die via USB gegevens van de Arduino uitlezen en tonen." \
  "2026" "2026-10-10 10:00:00"

create_project "includeme" "IncludeMe" \
  "Marketingwebsite en huisstijl voor een echte opdrachtgever." \
  "Solo: huisstijl, ontwerp en website" \
  "Huisstijl, webdesign, HTML, CSS" \
  "Huisstijl in paars en turquoise op basis van de wens van de opdrachtgever. De website is in ontwikkeling." \
  "2026" "2026-10-20 10:00:00"

echo "Menu aanmaken..."
wp menu create "Hoofdmenu" >/dev/null
wp menu item add-post hoofdmenu "$HOME_ID" --title="Home" >/dev/null
wp menu item add-post hoofdmenu "$ABOUT_ID" >/dev/null
wp menu item add-custom hoofdmenu "Projecten" "$WP_URL/projecten/" >/dev/null
wp menu item add-post hoofdmenu "$CONTACT_ID" >/dev/null
wp menu location assign hoofdmenu primary

wp rewrite flush
wp option add np_seeded 1 >/dev/null

echo ""
echo "Klaar! Open $WP_URL"
echo "Dashboard: $WP_URL/wp-admin (gebruiker: $WP_ADMIN_USER)"
