# Cosmic Mission Control

A small command-line program for managing a fleet of spacecraft and launching them on missions.
It is a practice project for modern PHP (8.0-8.4): an interface, an enum, readonly classes, exceptions and a trait.

## Requirements

- PHP 8.4 or later
- Composer

## Installation

```bash
composer install
```

## Usage

```bash
php bin/cosmic                        # show the available commands
php bin/cosmic fleet                  # list ships
php bin/cosmic planets                # list destinations
php bin/cosmic launch voyager mars    # run a mission
```

A launch succeeds when the spacecraft has enough fuel to cover the distance:

```text
$ php bin/cosmic launch voyager mars
Voyager reached Mars. Fuel burned: 6 (54 left).

$ php bin/cosmic launch pioneer mars
Pioneer needs 6 fuel but only has 5.
```

The fleet is read from `resources/fleet.json`. Fuel is not saved between runs, so every
launch starts from the fuel stored in that file.

The program exits with 0 on success and 1 on any error. Error messages go to stderr.

## Tests

```bash
composer test
```

## Where to find each language feature

| Feature | Example |
|---------|---------|
| Interface | `src/Service/FleetStorageInterface.php` |
| Enum | `src/Model/Planet.php` |
| Readonly class | `src/Model/MissionReport.php`, `src/Service/MissionControl.php` |
| Exceptions | `src/Exception/`, caught in `src/Console/Application.php` |
| Trait | `src/Model/CarriesCargoTrait.php` |
| Abstract class and inheritance | `src/Model/AbstractSpacecraft.php` and its three subclasses |
| Property hooks, asymmetric visibility (8.4) | `src/Model/AbstractSpacecraft.php` |
