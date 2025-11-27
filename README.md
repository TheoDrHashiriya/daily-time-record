# Daily Time Record
A simple time recording system with CRUD functionality, built on the MVCS design.
## Main Features
- Role-based access control
- Responsive dashboard layout
- Dark Mode
## Technical Stuff
- MVCS (Models, Views, Controllers, Services) design pattern
- PHP routing and the use of .htaccess for SEO-friendly URLs
- Passwords are hashed when stored in the database for security
- Fully normalized, 5NF-compliant database design
### CRUD Access Overview
| Role     |                 Create                 | Read                              | Update                            | Delete                            |
| -------- | :------------------------------------: | --------------------------------- | --------------------------------- | --------------------------------- |
| Admin    | Register Users (of all roles), Records | Own profile, Any user, Any record | Own profile, Any user, Any record | Own profile, Any user, Any record |
| Manager  |              Own records               | Any user, Any record              | Any user, Any record              | None                              |
| Employee |              Own records               | Any record                        | None                              | None                              |
### Simple Entity-Relationship Diagram
```mermaid
---
config:
  layout: elk
---
erDiagram
	direction TB
	department {
		int id PK ""  
		string department_name UK ""  
		string abbreviation UK ""  
		time standard_time_in  ""  
		time standard_time_out  ""  
		datetime created_at  ""  
	}
	user_role {
		int id PK ""  
		string role_name UK ""  
	}
	notification {
		int id PK ""  
		string title  ""  
		string content  ""  
		boolean has_been_read  ""  
		datetime created_at  ""  
		int created_by FK "references user(id)"  
	}
	event_record_type {
		int id PK ""  
		string type_name UK ""  
	}
	full_name {
		int id PK ""  
		string first_name  ""  
		string middle_name  ""  
		string last_name  ""  
	}
	employee_type {
		int id PK ""  
		string type_name  ""  
	}
	user {
		int id PK ""  
		int full_name FK "references full_name(id)"  
		string username UK ""  
		string email UK ""  
		string hashed_password  ""  
		datetime created_at  ""  
		int employee_type FK "references employee_type(id)"  
		int user_role FK "references user_role(id)"  
		int department FK "references department(id)"  
		int created_by FK "references user(id)"  
	}
	event_record {
		int id PK ""  
		int user_id FK ""  
		datetime event_time  ""  
		int event_type FK "references event_record_type(id)"  
	}

	user||--o{user:"creates"
	user_role||--o{user:"of"
	user}|--||department:"is in"
	user||--o{notification:"creates"
	user||--o{event_record:"logs"
	event_record_type||--o{event_record:"has"
	full_name||--|{user:"of"
	employee_type}|--|{user:"of"
```
### MVCS Flow
```mermaid
---
config:
  layout: dagre
---
flowchart TB
    db["Database"] -- data --> mod["Model"]
    serv["Service"] -- business logic --> con["Controller"]
    mod -- data access --> serv
    mod -- data for rendering --> con
    view["View"] -- user form data --> con
    con -- render data --> view
    mod -- handle --> db
    con -- request --> mod
```