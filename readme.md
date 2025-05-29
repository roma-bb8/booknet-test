# BookNet

[BookNet Test](https://github.com/booknet-company/test)

- Схема бази даних: `.sql/00-schema.sql`
- Початкові дані: `.sql/01-data.sql`
- Точка входу: `app/bin/console`
- Команда яка виводить дані: `app/src/Commands/PaymentMethodList.php`
- Клас `PaymentTypeSelector` переїхав: `app/src/Services/PaymentType/Selector.php`

Розпочати проєкт:

```shell
make start
```

Виконати тест:

```shell
make test
```

Зайти в контейнер:

```shell
make app
```

Зупинити та видалити контейнери проекту:

```shell
make stop
```

Пару картинок:

![screen_1.png](docs/screen_1.png)

![screen_2.png](docs/screen_2.png)
