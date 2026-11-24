# Cosmic Mission Control

A small command-line program for managing a fleet of spacecraft and launching them on missions.
It is a practice project for modern PHP (8.0-8.4): an interface, enums, readonly classes, exceptions and a trait.

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
Mission accomplished: Voyager reached Mars.
Fuel burned: 6 (54 left).

$ php bin/cosmic launch pioneer mars
Mission aborted: Mars is 55 million km away, but Pioneer can only travel 50.
```

The fleet is read from `resources/fleet.json`. Fuel is not saved between runs, so every
launch starts from the fuel stored in that file.

### Exit codes

| Code | Meaning |
|------|---------|
| 0 | Success |
| 1 | The mission was aborted, or the spacecraft, planet or fleet data was not valid |
| 2 | The command line was wrong: an unknown command or a missing argument |

Normal output goes to stdout and error messages go to stderr.

## Tests

```bash
composer test
```

## Where to find each language feature

| Feature | Example |
|---------|---------|
| Interface | `src/Service/FleetStorageInterface.php`, `src/Console/CommandInterface.php` |
| Enum | `src/Model/Planet.php` (backed), `src/Model/MissionStatus.php` (pure) |
| Readonly class | `src/Model/MissionReport.php`, `src/Model/SpacecraftData.php` |
| Exceptions | `src/Exception/`, caught in `src/Console/Application.php` |
| Trait | `src/Model/CarriesCargoTrait.php` |
| Abstract class and inheritance | `src/Model/AbstractSpacecraft.php` and its three subclasses |
| Property hooks, asymmetric visibility (8.4) | `src/Model/AbstractSpacecraft.php` |
