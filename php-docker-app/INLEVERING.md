# Docker PHP & MySQL opdracht

## 1. GitHub repository

Link naar de openbare of school-GitHub repository:

**GitHub-link:** vul hier de link in zodra de repository is gepusht.

---

## 2. Screenshots

### Screenshot Windows

Plaats hier een duidelijke screenshot van een werkende `http://localhost:8080` op Windows.

### Screenshot macOS

Plaats hier een duidelijke screenshot van een werkende `http://localhost:8080` op macOS.

---

## 3. Onderzoeksvragen

### Stap 1 — OS-onderzoek

**Waarom heeft Docker WSL2 nodig op Windows?**

Docker-containers gebruiken normaal Linux-technieken zoals namespaces, cgroups en een Linux-kernelomgeving. Windows heeft die Linux-kernel niet standaard op dezelfde manier beschikbaar. WSL2 geeft Windows een lichte Linux-omgeving met een echte Linux-kernel, waardoor Docker Linux-containers goed en stabiel kan draaien. Daarom gebruikt Docker Desktop op Windows meestal de WSL2-backend.

**Architectuurcontrole macOS:**

Met het commando `uname -m` kun je controleren welke processorarchitectuur je Mac gebruikt.

- `x86_64` betekent dat je op een Intel Mac werkt.
- `arm64` betekent dat je op Apple Silicon werkt, zoals M1, M2 of M3.

---

### Onderzoeksvraag 1

**Wat gebeurt er als je de regel `RUN docker-php-ext-install pdo pdo_mysql` weglaat uit de Dockerfile en de applicatie probeert te starten? Welke specifieke PHP-foutmelding genereert de browser dan?**

Als deze regel wordt weggelaten, wordt de MySQL-driver voor PDO niet geïnstalleerd in de PHP-container. De PHP-code probeert wel verbinding te maken met MySQL via PDO, maar PHP heeft dan geen geschikte MySQL-driver beschikbaar.

De browser toont dan meestal een fout zoals:

```text
Connectie mislukt: could not find driver
```

De oorzaak is dus dat `pdo_mysql` ontbreekt. Zonder deze extensie kan PHP geen PDO-verbinding maken met een MySQL-database.

---

### Onderzoeksvraag 2

**Waarom werkt `host=db` in het PHP-script wel, maar `host=localhost` niet? Naar welk specifiek onderdeel in je `docker-compose.yml` verwijst de naam `db`?**

`host=db` werkt omdat Docker Compose automatisch een intern netwerk maakt voor alle services in `docker-compose.yml`. Binnen dat netwerk kunnen containers elkaar bereiken via hun servicenaam.

In dit project verwijst `db` naar deze service in `docker-compose.yml`:

```yaml
services:
  db:
    image: mysql:8.0
```

De naam `db` is dus de DNS-naam van de MySQL-container binnen het Docker Compose-netwerk.

`localhost` werkt hier niet, omdat `localhost` binnen de PHP-container naar de PHP-container zelf verwijst. De database draait niet in dezelfde container, maar in een aparte MySQL-container. Daarom moet de PHP-container verbinden met `db` in plaats van `localhost`.

---

### Onderzoeksvraag 3

**Wijzig de tekst in de `<h1>` tag in je `src/index.php` bestand en ververs de browser. Zie je de wijziging direct zonder de container te herstarten? Welke regel in de `docker-compose.yml` maakt dit mogelijk en hoe werkt dit mechanisme?**

Ja, de wijziging is direct zichtbaar na het verversen van de browser. Je hoeft de container niet opnieuw te starten.

Dit komt door deze regel in `docker-compose.yml`:

```yaml
volumes:
  - ./src:/var/www/html
```

Dit is een bind-mount. De lokale map `./src` op je computer wordt gekoppeld aan de map `/var/www/html` in de PHP-container. Daardoor ziet Apache/PHP in de container direct de bestanden die jij lokaal aanpast. Als je `index.php` wijzigt en de browser ververst, wordt de nieuwste versie van het bestand direct gebruikt.

---

## 4. Gebruikte bestanden

### `src/index.php`

```php
<?php
$host = 'db';
$db   = 'school_db';
$user = 'root';
$pass = 'geheim123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1 style='color: green;'>🎉 Connectie succesvol! Docker netwerkkopie werkt!</h1>";
} catch (PDOException $e) {
    echo "<h1 style='color: red;'>❌ Connectie mislukt:</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
```

### `Dockerfile`

```dockerfile
FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY src/ /var/www/html/
EXPOSE 80
```

### `docker-compose.yml`

```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:8.0
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: geheim123
      MYSQL_DATABASE: school_db
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

---

## 5. Conclusie

Met Docker en Docker Compose draait de PHP-applicatie samen met een MySQL-database in aparte containers. De webcontainer gebruikt de servicenaam `db` om de databasecontainer te vinden. Door de bind-mount `./src:/var/www/html` zijn wijzigingen in de PHP-code meteen zichtbaar zonder de container opnieuw te bouwen of te herstarten. Hierdoor werkt dezelfde ontwikkelomgeving op verschillende systemen, zoals Windows en macOS.
