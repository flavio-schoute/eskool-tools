# eSkool Tools

Todo:

-   Own directory for docker in .dev folder instead of php/docker for better readability

Idea:
-> Action
-> Call service

--> Action -> Repository

-- Brainstorm
-> Customers table based on unique email

    -> Start workflow

## Roles

-   Admin
-   Closer
-   Setter

## Permissions

-   Admin
    -   All Permissions but with some extra
        -   Change comission
        -   Remove closer/ setter or disable account
-   Closer
    -   Claim order
    -   View order
    -   Commission breakdown
    -   Comission export
-   Setter
    -   Same permissions as closer

## Commands:

```bash
make fix
```

```bash
make lint
```

### Inspiration sidebar:

> https://tailwindui.com/components/application-ui/application-shells/sidebar

--
Debtor management URL:

-   Als je op een factuurnummer klikt -> HTML Dialog openen met de factuur gegeven
    -> En link naar Mollie

==

Later als debteur tabel word bijgewerkt ook order status bij werken
