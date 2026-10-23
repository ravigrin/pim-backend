# PIM/MDM backend

Бэкенд PIM/MDM-системы: единый каталог товаров с выгрузкой в учётную систему (1С, OData)
и на маркетплейсы (Wildberries, Ozon), интеграцией с рекламной аналитикой и
фулфилмент-провайдерами, S3-хранилищем файлов и DWH на MSSQL.

Все хосты и креды в репозитории — заглушки, для запуска нужно заполнить `.env`.

## Стек

| Слой | Технологии |
|---|---|
| Язык / фреймворк | PHP 8.3, Symfony 6.3 (micro-kernel) |
| Приложение | RoadRunner (baldinof/roadrunner-bundle), CLI-команды Symfony Console |
| Данные | Doctrine ORM + DBAL (MSSQL), Doctrine Migrations, Redis (Messenger transport) |
| Архитектура | Модульный монолит, CQRS (три шины Messenger: command/query/event), доменные события |
| Безопасность | Symfony Security, LDAP (AD)-аутентификация, RBAC-роли |
| Хранилище файлов | Flysystem + AWS S3-совместимое хранилище |
| Качество кода | Deptrac (границы слоёв), Psalm, PHPStan, Rector, PHP-CS-Fixer, PHPUnit |
| CI/CD | GitLab CI, Docker / Docker Compose |

## Структура

```
module/
  shared/       # ядро: CommandBus/QueryBus/EventBus, AggregateRoot, PersistenceRepository
  pim/          # мастер-данные: товары, атрибуты, юниты, бренды, пользователи, авторизация
  ozon/         # интеграция с Ozon Seller API
  wildberries/  # интеграция с Wildberries (каталоги, атрибуты, цены, экспорт карточек)
  ad-analytics/ # адаптер рекламной аналитики (провайдер-агностичный, через реестр провайдеров)
  fulfillment/  # адаптер фулфилмент-провайдеров (реестр провайдеров + REST-джентрик)
  files/        # загрузка/шаблоны файлов (S3)
src/            # App-слой: Kernel, Security, контроллеры V2, фабрики запросов
config/         # сервисы, шины, security, doctrine, flysystem, monolog
api/            # JetBrains .http-коллекции для ручной проверки эндпоинтов
docs/           # заметки по модулям, БД и workflow
migrations/     # Doctrine migrations (MSSQL dialect)
```

Каждый бизнес-модуль повторяет один скелет:

```
Domain/           Entity, Repository-интерфейсы, Service, Event, ValueObject
Application/      Command/<UseCase>/{Command,Handler}, Query/<UseCase>/{Query,Fetcher,Dto}
Infrastructure/   Doctrine/Guzzle/Ldap/Dwh реализации портов Domain
EndPoint/         Http-контроллеры, Console-команды
```

## Быстрый старт (dev)

```bash
docker compose up -d --build        # php(roadrunner), redis, mssql-edge
docker compose exec php composer install
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate
# заполнение справочников:
docker compose exec php composer pim-init-data
docker compose exec php php bin/console wb:import catalog
```

Скопируйте `.env.example` в `.env` и заполните секреты (`DATABASE_PWD`, `OZON_API_KEY`,
`AD_ANALYTICS_TOKEN`, `AWS_*`, `TG_BOT_TOKEN`, `LDAP_*`) — в репозитории только заглушки.

## Контроль качества

```bash
composer cs-fix     # PHP-CS-Fixer
composer psalm      # Psalm
composer phpstan    # PHPStan
composer deptrac    # границы слоёв (блокирующий шаг CI)
composer tests      # PHPUnit
```

## Примечание

Товарные знаки третьих сторон (1С, Wildberries, Ozon и др.) принадлежат их владельцам
и используются только как названия интегрируемых систем.
