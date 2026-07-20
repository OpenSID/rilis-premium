## [13.1.5](https://github.com/yajra/laravel-datatables/compare/v13.1.4...v13.1.5) (2026-07-03)


### Bug Fixes

* avoid double wildcarding column search keywords ([f6ef379](https://github.com/yajra/laravel-datatables/commit/f6ef3794118be4e56c68bf6c57bcb797e1e1b8ed))
* handle schema-qualified eloquent table searches ([a0516eb](https://github.com/yajra/laravel-datatables/commit/a0516ebdf46570b3c5c6a278ca0ded890bc5d9a8))
* type schema-qualified table search helper ([25622d2](https://github.com/yajra/laravel-datatables/commit/25622d28fc19c803357b593163843fcf39262fcf))

## [13.1.4](https://github.com/yajra/laravel-datatables/compare/v13.1.3...v13.1.4) (2026-07-03)


### Bug Fixes

* allow non-latin characters in column names ([8cdb6c8](https://github.com/yajra/laravel-datatables/commit/8cdb6c84f1f92d1f88f52eab74444127d9c30378))
* **security:** pass HTTP request to API resources ([0a1aa50](https://github.com/yajra/laravel-datatables/commit/0a1aa50b3932158a32c8b2723827164cc7c40a47))

## [13.1.3](https://github.com/yajra/laravel-datatables/compare/v13.1.2...v13.1.3) (2026-06-30)


### Bug Fixes

* **processor:** prevent duplicate format() call and pass null for missing columns ([365137c](https://github.com/yajra/laravel-datatables/commit/365137c8badfe1e0ef68258fe51c6e0980176bd1))
* **query:** honor searchable flag in ColumnControl search ([4c92c10](https://github.com/yajra/laravel-datatables/commit/4c92c1029c4462b60aec490e34e5e4a24f937d7d))

## [13.1.2](https://github.com/yajra/laravel-datatables/compare/v13.1.1...v13.1.2) (2026-05-19)


### Bug Fixes

* column array notation [#3282](https://github.com/yajra/laravel-datatables/issues/3282) ([edfcb7e](https://github.com/yajra/laravel-datatables/commit/edfcb7e73f59c2e837a329093772065349cecb59))

## [13.1.1](https://github.com/yajra/laravel-datatables/compare/v13.1.0...v13.1.1) (2026-05-16)


### Bug Fixes

* replace fragile denylist with allowlist for column name validation ([ce833c2](https://github.com/yajra/laravel-datatables/commit/ce833c2a5034fcf5609c8c059b90aab1ef38e4b2))

# [13.1.0](https://github.com/yajra/laravel-datatables/compare/v13.0.0...v13.1.0) (2026-05-15)


### Bug Fixes

* phpstan offsetAccess.invalidOffset ([be54129](https://github.com/yajra/laravel-datatables/commit/be54129758c02b69549facb3e73eb8a356f456b3))
* sql injection on orderByNullsLast ([58cc635](https://github.com/yajra/laravel-datatables/commit/58cc635569b9e905943dd82ecf5b93bf60edf80e))


### Features

* add context7 configuration file ([1977fea](https://github.com/yajra/laravel-datatables/commit/1977feaa9c9e074a0ab9e68bf3807b10c5c6dab6))
* restore context7 configuration file ([5e5b0b5](https://github.com/yajra/laravel-datatables/commit/5e5b0b5f1e098034e2a1c414b8895f463aad9829))

### v13.0.0 - 2026-03-18

- feat: Laravel 13 Compatibility #3274

[Unreleased]: https://github.com/yajra/laravel-datatables/compare/v13.0.0...master
