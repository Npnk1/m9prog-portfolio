# AI-verantwoording

## Hoe ik AI heb gebruikt

Ik heb Claude (claude.ai) en Claude Code gebruikt als hulpmiddel bij dit project.
De eerste versie van de code is met Claude gegenereerd op basis van de opdrachtomschrijving:
het theme, de Docker-configuratie en het seed-script. Daarna heb ik het project zelf ingericht,
gecontroleerd en aangepast.


## Gegenereerd met AI

- `docker-compose.yml`, `.env.example`, `.gitignore`, `.gitattributes`
- `seed/` (script, teksten en tijdelijke afbeeldingen)
- `wp-content/themes/np-portfolio/` (alle theme-bestanden)
- `README.md`

## Wat ik zelf heb gedaan

- Bepaald welke projecten in mijn portfolio komen en RijSchool vervangen door het DP-project.
- De informatie voor de projectteksten aangeleverd en de teksten gecontroleerd en ingekort,
  bijvoorbeeld geen aantallen of details van de klant.
- GitHub CLI geïnstalleerd, ingelogd en de private repository opgezet.
- De site lokaal in de browser gecontroleerd.

## Nog te doen

Als ik dit gedaan heb, verplaats ik het naar "Wat ik zelf heb gedaan":

- [ ] `inc/cpt-project.php` en `inc/project-meta.php` doorgenomen en zelf een veld toegevoegd
- [ ] `inc/contact.php` doorgenomen: hoe een bericht van formulier naar dashboard gaat
- [ ] Een kleur aangepast in `src/scss/_tokens.scss` en opnieuw gebouwd met `npm run build`
- [ ] Afbeeldingen vervangen door echte screenshots
