



# eLearn — plateforme d’apprentissage en ligne

Application e-learning composée d’une API Laravel et d’une interface web Nuxt. Elle permet de gérer des cours et des leçons, de passer des quiz et de suivre la progression des apprenants.

## Fonctionnalités

- Inscription et connexion avec authentification par jeton Laravel Sanctum.
- Gestion des utilisateurs, cours, leçons, quiz et réponses.
- Import de quiz depuis des fichiers JSON.
- Passation de quiz, enregistrement des réponses et consultation des résultats.
- Suivi de la progression des inscriptions et tableau de bord apprenant.
- Création d’objectifs personnels liés aux cours.
- Génération de questions par IA via l’API Anthropic (configuration facultative).

## Structure du dépôt

- `elearnapi/` — API REST Laravel 11, migrations, tests et gestion des tâches en arrière-plan.
- `elearnuxt/` — interface principale Nuxt 4 / Vue 3.
- `elearnapi/vue-project/` — autre client Vue/Vite présent dans le dépôt.

## Prérequis

- PHP 8.2 ou supérieur et Composer.
- Node.js et npm (ou pnpm) pour l’interface Nuxt.
- SQLite par défaut, ou un autre moteur pris en charge par Laravel et configuré dans l’environnement.

## Installation et lancement en développement

### 1. API Laravel

Dans un terminal :

```bash
cd elearnapi
composer install
cp .env.example .env
php artisan key:generate
```

La configuration d’exemple utilise SQLite. Créez le fichier de base de données s’il n’existe pas :

```bash
touch database/database.sqlite
```

Configurez ensuite la base dans `elearnapi/.env` si vous utilisez autre chose que SQLite, puis lancez les migrations :

```bash
php artisan migrate
```

**À vérifier avant la première migration :** la migration de création des leçons semble incomplète dans l’état actuel du dépôt. Elle ne déclare pas `course_id` et tente de supprimer une clé étrangère pendant la création de la table. Corrigez ou vérifiez cette migration avant de vous appuyer sur une installation vierge.

L’interface Nuxt pointe par défaut vers `http://localhost:8001/api`. Lancez donc l’API sur le port 8001 :

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

L’API est alors accessible à `http://localhost:8001/api`.

### 2. Interface Nuxt

Dans un second terminal, à la racine du dépôt :

```bash
cd elearnuxt
npm install
npm run dev
```

Nuxt est configuré sur le port `3001` : ouvrez `http://localhost:3001`.

L’adresse de l’API est définie dans `elearnuxt/nuxt.config.ts` (`runtimeConfig.public.apiUrl`), actuellement `http://localhost:8001/api`. Adaptez-la si l’API est hébergée ailleurs. Certains services historiques peuvent utiliser une adresse différente ; vérifiez leur configuration si des appels API échouent.

### 3. Tâches en arrière-plan (si nécessaire)

La configuration fournie utilise une file d’attente en base de données. Pour traiter les tâches asynchrones — par exemple le recalcul de progression après certains imports — lancez un worker depuis `elearnapi/` :

```bash
php artisan queue:work
```

## Configuration facultative

- **Base de données :** modifiez les variables `DB_*` dans `elearnapi/.env`. SQLite est le choix par défaut.
- **Génération IA :** définissez `ANTHROPIC_API_KEY` dans `elearnapi/.env` pour utiliser la génération de quiz. La route est limitée en fréquence et nécessite une authentification.
- **Accès depuis un autre appareil :** mettez à jour l’URL d’API côté Nuxt et les origines autorisées dans la configuration CORS de Laravel.

Ne publiez jamais le fichier `.env` ni ses clés ou secrets.

## API — aperçu

Les routes sont définies dans `elearnapi/routes/api.php`. Exemples :

- `POST /api/register` et `POST /api/login` — création de compte et connexion.
- `/api/courses`, `/api/lessons`, `/api/quizzes` — ressources pédagogiques.
- `GET /api/dashboard` — tableau de bord authentifié.
- `POST /api/lessons/{lesson}/attempts` — démarrer une tentative authentifiée.
- `POST /api/attempts/{attempt}/answers` et `POST /api/attempts/{attempt}/finish` — répondre et terminer une tentative.
- `GET /api/enrollments/{course}/pourcentage` — progression d’un cours.

Les routes protégées attendent un jeton Sanctum dans l’en-tête `Authorization: Bearer <jeton>`.

## Tests et production

Depuis `elearnapi/`, exécutez les tests PHP avec :

```bash
php artisan test
```

Pour compiler les ressources du projet Laravel :

```bash
npm install
npm run build
```

Pour compiler l’interface Nuxt, depuis `elearnuxt/` :

```bash
npm run build
```

Consultez la documentation Laravel et Nuxt pour les détails de déploiement. En production, configurez notamment les variables d’environnement, les origines CORS, la base de données et le traitement des files d’attente.
