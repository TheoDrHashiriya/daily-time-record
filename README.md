# Daily Time Record
A simple time recording system with CRUD functionality and built on the MVC design.
## Main Features (TBI)
- CRUD functionality scoped for admins and regular users
- Dark Mode with smooth transitioning
- Role-based authentication
- Responsive dashboard layout
## Technical Stuff
- Implementation of MVC design
- PHP routing and the use of .htaccess for SEO-friendly URLs
## Simple Entity-Relationship Diagram
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