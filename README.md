# Parkeasy (showcase)

PHP 8.1+ / MySQL 8 parking reservation demo. Not meant for real use: payment is fake.

## Setup

1. Put this folder at `htdocs/web_parking_reservation` (the paths in the code and CSS expect that name).
2. Import `database/parking_db.sql` (creates `parking_db` with seed data).
   Already imported the old dump? Run `database/migrate_existing.sql` instead.
3. Check `app/config/database.php` (root / empty password by default) and `app/config/app.php`.
4. Open `http://localhost/web_parking_reservation/public/`.

## Accounts

| Who      | Where                  | Login                               |
|----------|------------------------|-------------------------------------|
| Customer | `?page=auth&step=signup` | sign up yourself                  |
| Operator | `?page=operator`       | operator@parkeasy.test / password   |
| Admin    | `?page=admin`          | admin@parkeasy.test / password      |

Change the staff passwords from the admin page. Forgot password is demo-only: the OTP is shown in the alert, no email is sent.

## Reservation rules

- A customer saves plate numbers in Profile, then clicks a free slot and picks a plate and a time.
- The time can be at most 2 hours from now (15-minute steps).
- Cannot be cancelled within 1 hour of the reserved time.
- Earliest time is 5 minutes from now. Only one active (unpaid or paid) reservation per user.
- Unpaid: must be paid before 1 minute before the reserved time (the "pay before" time on the invoice), otherwise it becomes Expired.
- Payment is fake: "Check Payment Status" marks the invoice as paid.
- Paid: expires 1 hour after the reserved time if the operator has not confirmed it.
- The operator confirms a paid reservation, which makes it Completed.
- Expiry runs on every request, no cron job.
