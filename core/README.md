# Oxygen Gym LB

A custom gym management system built with Laravel 12.

## Features

### Authentication & Authorization

* **Secure Login/Logout:** Session-based authentication for the web UI.
* **Role-Based Access Control:** Roles (Admin, Receptionist, Trainer, Member) via Spatie Laravel Permission.
* **User Management:** Admins can create, edit, delete users, and assign roles.
* **Admin Override:** Admins have full access to all features automatically.

### Dashboard

* **Analytics Overview:** Quick stats for total members, active/expired subscriptions, and monthly revenue.

### Member Management

* **Member Directory:** List all members with search (by name/phone) and status filtering (Active, Non-active, Frozen, All).
* **CRUD Operations:** Add, edit, and soft-delete members.
* **Extended Member Fields:** Medical notes; membership status **Active / Inactive / Frozen**; member tags (VIP, Athlete, Intermediate, Beginner); preferred training time (**AM** or **PM**).
* **Member Profile:** Detailed view with tags, medical notes, training time, subscriptions (with paid vs. due amounts), and recent payment history. Highlights **payment due** in red when amount paid is below the subscription’s final price.

### Subscription Plans

* **Plan Management:** Create and edit plans (name, duration in days, price, **plan type**: Individual or **Couple**).
* **Sample Data:** Seeder includes **Daily** (one calendar day), **Monthly**, **Quarterly**, **Yearly**, and **Couple Monthly** (run `PlansSeeder` or full `db:seed` to refresh).
* **Plan Details:** View a plan and see all members currently subscribed to it.

### Subscriptions

* **Assign Subscriptions:** Enroll members with start date; **end date** for plan-based subs is computed as **inclusive calendar days**: `duration_days = N` means access from the start date through **N** consecutive calendar dates (e.g. **Daily** with `N = 1` is the **same** calendar day only; after midnight in `config('app.timezone')`, the next calendar date is no longer covered). **Custom (no plan)** subscriptions use the end date you enter.
* **New vs Renewal:** First subscription for a member is marked **New**; later ones are **Renewal**.
* **Discounts:** Optional discount amount; **final price** = plan price minus discount; initial payment uses the final price (discount cannot exceed plan price; final amount must be positive).
* **Create Subscription UI:** Live preview of amount due after discount.
* **Status Tracking:** Active or expired per subscription dates.
* **Automated Expiration:** Scheduled command updates expired subscriptions daily (`subscriptions:expire`).

**Member status vs subscription status**

| Concept | Where it lives | When it is “active” |
|--------|----------------|---------------------|
| **Member status** | `members.status` (`active` / `inactive` / `frozen`) | Set **manually** on create/edit in the UI. **Not** updated automatically when a subscription ends. The members list filter “Active” uses this field. |
| **Subscription status** | `subscriptions.status` (`active` / `expired`) | **Active** while the subscription still covers **today** (app timezone): listings use `end_date >= today` and `status = active`; `subscriptions:expire` sets `expired` when `end_date` is **before** today. |

A member can be **active** in the directory but have no current subscription (or only expired rows), or **inactive** while old subscription records still exist—staff use **member status** for operational rules unless you add automation later.

**Note:** Subscriptions created **before** inclusive calendar-day logic may have different `end_date` lengths than new enrollments on the same plan name; only new plan-based subscriptions use the current formula.

### Payments

* **Payment Tracking:** Initial payment recorded when a subscription is created (method, paid-at).
* **Payment History:** List past payments per member; subscription rows show total paid vs. **final price** for quick balance checks.

### UI & Design

* **Custom Branding:** Login page with blue–green gradient, O2 watermark pattern, high-res logo, and rounded “bubble” login card.
* **Navbar:** Gradient **Oxygen Gym** brand text; black top bar; **offcanvas sidebar** on small screens (hamburger menu).
* **App Layout Watermark:** Faint O2 logo in the main content area.
* **Responsive Layout:** Bootstrap 5 sidebar + main content; dark sidebar/nav styling.
* **Flash Notifications:** Success and error alerts; optional auto-dismiss after a few seconds on pages that include the flash partial.

### Architecture

* **Clean Architecture:** Separation of concerns using Controllers, Services, and Repositories.
* **API Ready:** Includes `/api/v1/` endpoints for core features (Auth, Members, Plans, Subscriptions, Payments).
* **UUIDs:** Uses secure UUIDs for all database primary keys.
