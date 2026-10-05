# LightWork: freelance marketplace prototype (Bitcoin Lightning concept)

A front-end prototype for a freelance marketplace where employers post jobs, freelancers offer their services and
payments are settled over the Bitcoin **Lightning Network**. It is an early web project of mine, written a couple of years ago in plain
HTML, CSS and JavaScript, which is why the design is simple. The interface is in Spanish.

> **Status: prototype.** This repository contains the front end only: no back end, database or real Lightning integration
> is included. All listings are hard-coded and the chat and payment flows are simulated in the browser. See [What is real and what is simulated](#what-is-real-and-what-is-simulated).

## Pages

| Page | What it shows |
|---|---|
| `index.html` | Landing page: how the platform works, benefits of Lightning payments, featured freelancers, blog previews and a rotating testimonial banner |
| `jobs.html` | Job board with four sample crypto/blockchain job offers and a live search box |
| `freelancers.html` | Freelancer directory with sample profiles and a live search box |
| `chat.html` | Chat window between employer and freelancer |
| `payment.html` | "Pay with Lightning" form (invoice and amount in satoshis) |
| `login.html` | Sign-in / sign-up screen with a sliding panel animation (not linked from the other pages; open it directly) |

## What is real and what is simulated

| Feature | Implemented in this repository | Not included |
|---|---|---|
| Search on jobs and freelancers | Case-insensitive text filter over the listings, on click or Enter (vanilla JavaScript, DOM) | Server-side search, filters, pagination |
| Chat | Messages are added to the window with a timestamp; an automatic canned reply appears after 1 s | Real messaging, users, persistence |
| Lightning payment | Form submission, loading state and a confirmation message after 3 s | Any connection to a Lightning node, invoice validation or real payment |
| Login / sign-up | Visual toggle between the two panels | Authentication, accounts, sessions |
| Landing page | Section fade-in on scroll (IntersectionObserver), rotating testimonials every 5 s, button hover effect | The testimonials, freelancer ratings and blog posts are placeholder content |
| Persistence | None | Database, API |

The "Explorar ahora" button on the landing page has no action yet.

## Run it

No build step. Open `index.html` in a browser, or serve the folder locally:

```bash
python3 -m http.server 8000
# then open http://localhost:8000
```

An internet connection is needed for the Google Fonts, Font Awesome, Animate.css, a hero photo from Unsplash and
placeholder portraits from randomuser.me, which are loaded from external servers.

## Known limitations

* Desktop layout only: the pages declare a viewport but the CSS has no media queries, so they are not responsive.
* Styles are split between `styles.css` (landing page) and inline `<style>` blocks (other pages).
* Sample data is hard-coded in the HTML.
* Brand naming was unified to "LightWork" across pages when I tidied the repository; the pages had been named differently during development.

## What I would do next

A real version would need a back end with a relational database (users, jobs, applications, messages, payments),
authentication, real-time chat (WebSockets), and a Lightning payment integration (invoice generation and
verification through a node or a payment provider), plus a responsive redesign and a single shared stylesheet.
