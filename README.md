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
php bin/cosmic fleet                  # list ships
php bin/cosmic planets                # list destinations
php bin/cosmic launch voyager mars    # run a mission
```

The commands are added step by step; see the commit history.
