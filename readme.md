# CQRS

CQRS example site that simply has users and keeps track of user events.

## System

##### MongoDB

Stores events
Events replayed from here for reporting and analytics

##### MySQL

Has snapshot of current state
Events can be replayed to update current state
Data is always read from here

##### Redis

Cache database

##### Laravel

CQRS system via events
Caching may be included so that MySQL isn't always read from
