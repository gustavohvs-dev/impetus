CREATE VIEW vw_users AS SELECT
    USERS.id,
    USERS.status,
    USERS.name,
    USERS.email,
    USERS.username,
    USERS.permission,
    USERS.isConfirmedEmail,
    USERS.companyId,
    COMPANIES.corporateName,
    USERS.createdAt,
    USERS.updatedAt
FROM users USERS
LEFT JOIN companies COMPANIES ON USERS.companyId = COMPANIES.id;