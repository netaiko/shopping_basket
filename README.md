# ShoppingBasket

This is a PHP-based application for managing a shopping basket,
integrating design patterns like Strategy, Dependency Injection and Repository.
It includes:

- **PHP 8.2**
- **Docker** and **Docker Compose** for containerized environments
- **PHPStan** for static code analysis
- **PHPUnit** for unit and integration testing

## Setup

### Prerequisites

Ensure you have the following installed on your machine:

- **Docker** and **Docker Compose**: [Installation Guide](https://docs.docker.com/get-docker/)

### Steps to Run the Project

1. **Clone the repository**:

   ```bash
   git clone https://github.com/netaiko/shopping_basket.git
   cd shopping_basket
   ```

2. **Build the Docker containers**:

   ```bash
   docker-compose build
   ```

3. **Start the containers**:

   ```bash
   docker-compose up
   ```

   The application can be checked at:  
   [http://localhost:8080](http://localhost:8080)

4. **Run Tests**:

   ```bash
   docker-compose run web vendor/bin/phpunit --testdox
   ```

5. **Run Static Analysis with PHPStan**:

   ```bash
   docker-compose run web vendor/bin/phpstan analyse src
   ```

## How It Works

- PHP 8.2 is used in the Docker container.
- Apache serves the PHP files.
- Composer manages dependencies.
- PHPUnit is used for testing.
- PHPStan performs static analysis to catch bugs and enforce coding standards.

## Project Structure

- `src/` – Application source code
    - `Domain/` – Business logic (models, rules, repositories)
    - `Services/` – Application services
    - `Infrastructure/` – Implementations of domain interfaces
- `config/` – Configuration files (catalog and delivery costs)
- `tests/` – PHPUnit tests
- `Dockerfile` – Docker configuration for PHP + Apache
- `docker-compose.yml` – Docker service definitions
- `composer.json` – Project dependencies
- `phpunit.xml` – PHPUnit configuration

## Design Patterns

- **Strategy Pattern**:
    - **Discount Rules**:
        - Interface: `DiscountRule.php`
        - Implementation: `BuyOneGetOneHalfPrice.php`
        - Easily extendable to other discount strategies.
    - **Delivery Cost Calculation**:
        - Interface: `FeeRules.php`
        - Implementation: `DeliveryFeeRuleDefault.php`
        - Allows flexible delivery fee calculations based on different rules or business needs.

- **Dependency Injection**:
    - Used to decouple class dependencies, increase modularity, and improve testability.
    - Example: `BasketService` constructor requires injection of `Catalog`, `DiscountService` (for discount rules), and
      delivery fee rules (`FeeRules`).

- **Repository Pattern**:
    - Abstracts data access logic, separating it clearly from business logic.
    - Example (`Catalog`):
        - Interface: `Catalog.php`
        - Implementation: `InMemoryCatalog.php`
    - Designed to easily support multiple data sources (e.g., relational databases, NoSQL databases, APIs).

## Business Requirements

This project is a proof of concept for a sales system. It implements the following business rules:

- **Product Catalogue**:
    - Red Widget (R01) – $32.95
    - Green Widget (G01) – $24.95
    - Blue Widget (B01) – $7.95

- **Delivery Costs**:
    - Orders under $50: $4.95
    - Orders under $90: $2.95
    - Orders of $90 or more: Free delivery

- **Promotional Offer**:
    - Buy one Red Widget (R01), get the second one at half price.

- **Basket**:
    - The basket can be initialised with a catalogue, delivery rules, and discount rules.
    - Products are added using their product code.
    - `getTotalPrice()` calculates the final price including applicable offers and delivery.

- **Example Test Scenarios**:
    - `B01, G01` → **$37.85**
    - `R01, R01` → **$54.37**
    - `R01, G01` → **$60.85**
    - `B01, B01, R01, R01, R01` → **$98.27**

These scenarios are covered in the feature test Tests\Features\Basket\BasketTest.php to validate the basket’s logic
against expected outcomes.
