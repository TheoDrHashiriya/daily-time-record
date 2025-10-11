# Daily Time Record
A simple time recording system with CRUD functionality, built on the MVC design.
## Main Features (TBI)
- Role-based access control
- Responsive dashboard layout
- Dark Mode with smooth transitioning
## Technical Stuff
- MVC design pattern
- PHP routing and the use of .htaccess for SEO-friendly URLs
### CRUD Access Overview
| Role     |                   Create                    | Read                              | Update                            | Delete                            |
| -------- | :-----------------------------------------: | --------------------------------- | --------------------------------- | --------------------------------- |
| Admin    |        Users (of all roles), Records        | Own profile, Any user, Any record | Own profile, Any user, Any record | Own profile, Any user, Any record |
| Manager  | Users (managers and employees), Own records | Own profile, Any record           | Own profile                       | None                              |
| Employee |        (Register) Users, Own records        | Own profile, Own records          | Own profile                       | None                              |
### Simple Entity-Relationship Diagram
```mermaid
erDiagram
USER{
int id PK
string first_name
string last_name
string middle_name
string password
enum role "('admin', 'employee')"
}

DAILY_TIME_RECORD{
int id PK
int user_id FK
date record_date
datetime time_in
datetime time_out
}

USER||--o{DAILY_TIME_RECORD:"has"
```