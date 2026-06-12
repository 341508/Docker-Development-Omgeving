# PHP Docker App

Een eenvoudige PHP + MySQL stack met Docker en Docker Compose.

## Mappenstructuur

```text
php-docker-app/
├── src/
│   └── index.php
├── Dockerfile
├── docker-compose.yml
├── INLEVERING.md
└── README.md
```

## Starten

Open een terminal in de hoofdmap `php-docker-app` en voer uit:

```bash
docker compose up -d
```

Open daarna in je browser:

```text
http://localhost:8080
```

Als alles goed staat, zie je:

```text
🎉 Connectie succesvol! Docker netwerkkopie werkt!
```

## Stoppen

```bash
docker compose down
```

Wil je ook de database-data verwijderen, gebruik dan:

```bash
docker compose down -v
```

## Handige controlecommando's

```bash
docker --version
docker compose version
docker compose ps
docker compose logs web
docker compose logs db
```
# Docker-Development-Omgeving
