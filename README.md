# PHP Docker App

Voor deze opdracht heb ik een simpele PHP-applicatie gemaakt die verbinding maakt met een MySQL-database via Docker.

De applicatie draait op `localhost:8080`. Als de verbinding met de database goed werkt, krijg je een succesmelding in de browser.

## Bestanden

In dit project staan de volgende bestanden:

```text
php-docker-app/
├── src/
│   └── index.php
├── Dockerfile
├── docker-compose.yml
├── INLEVERING.md
└── README.md
```

## Project starten

Zorg dat Docker Desktop aan staat.

Open daarna een terminal in de projectmap en voer dit uit:

```bash
docker compose up -d
```

Open daarna in de browser:

```text
http://localhost:8080
```

Als alles goed werkt, verschijnt deze melding:

```text
🎉 Connectie succesvol! Docker netwerkkopie werkt!
```

## Project stoppen

Om de containers te stoppen:

```bash
docker compose down
```

Om ook de database-data te verwijderen:

```bash
docker compose down -v
```

## Controle

Handige commands die ik heb gebruikt:

```bash
docker --version
docker compose version
docker ps
docker compose logs web
docker compose logs db
```

## Korte uitleg

De PHP-code draait in een aparte web-container. De MySQL-database draait in een tweede container. Via `docker-compose.yml` worden deze containers samen gestart en kunnen ze elkaar bereiken via de servicenaam `db`.
