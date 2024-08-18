const Ziggy = {
    url: 'http://localhost',
    port: null,
    defaults: {},
    routes: {
        login: { uri: 'login', methods: ['GET', 'HEAD'] },
        logout: { uri: 'logout', methods: ['POST'] },
        'password.request': {
            uri: 'forgot-password',
            methods: ['GET', 'HEAD']
        },
        'password.reset': {
            uri: 'reset-password/{token}',
            methods: ['GET', 'HEAD'],
            parameters: ['token']
        },
        'password.email': { uri: 'forgot-password', methods: ['POST'] },
        'password.update': { uri: 'reset-password', methods: ['POST'] },
        register: { uri: 'register', methods: ['GET', 'HEAD'] },
        'user-profile-information.update': {
            uri: 'user/profile-information',
            methods: ['PUT']
        },
        'user-password.update': { uri: 'user/password', methods: ['PUT'] },
        'password.confirmation': {
            uri: 'user/confirmed-password-status',
            methods: ['GET', 'HEAD']
        },
        'password.confirm': { uri: 'user/confirm-password', methods: ['POST'] },
        'two-factor.login': {
            uri: 'two-factor-challenge',
            methods: ['GET', 'HEAD']
        },
        'two-factor.enable': {
            uri: 'user/two-factor-authentication',
            methods: ['POST']
        },
        'two-factor.confirm': {
            uri: 'user/confirmed-two-factor-authentication',
            methods: ['POST']
        },
        'two-factor.disable': {
            uri: 'user/two-factor-authentication',
            methods: ['DELETE']
        },
        'two-factor.qr-code': {
            uri: 'user/two-factor-qr-code',
            methods: ['GET', 'HEAD']
        },
        'two-factor.secret-key': {
            uri: 'user/two-factor-secret-key',
            methods: ['GET', 'HEAD']
        },
        'two-factor.recovery-codes': {
            uri: 'user/two-factor-recovery-codes',
            methods: ['GET', 'HEAD']
        },
        'terms.show': { uri: 'terms-of-service', methods: ['GET', 'HEAD'] },
        'policy.show': { uri: 'privacy-policy', methods: ['GET', 'HEAD'] },
        'profile.show': { uri: 'user/profile', methods: ['GET', 'HEAD'] },
        'other-browser-sessions.destroy': {
            uri: 'user/other-browser-sessions',
            methods: ['DELETE']
        },
        'current-user-photo.destroy': {
            uri: 'user/profile-photo',
            methods: ['DELETE']
        },
        'current-user.destroy': { uri: 'user', methods: ['DELETE'] },
        'api-tokens.index': {
            uri: 'user/api-tokens',
            methods: ['GET', 'HEAD']
        },
        'api-tokens.store': { uri: 'user/api-tokens', methods: ['POST'] },
        'api-tokens.update': {
            uri: 'user/api-tokens/{token}',
            methods: ['PUT'],
            parameters: ['token']
        },
        'api-tokens.destroy': {
            uri: 'user/api-tokens/{token}',
            methods: ['DELETE'],
            parameters: ['token']
        },
        'sanctum.csrf-cookie': {
            uri: 'sanctum/csrf-cookie',
            methods: ['GET', 'HEAD']
        },
        'ignition.healthCheck': {
            uri: '_ignition/health-check',
            methods: ['GET', 'HEAD']
        },
        'ignition.executeSolution': {
            uri: '_ignition/execute-solution',
            methods: ['POST']
        },
        'ignition.updateConfig': {
            uri: '_ignition/update-config',
            methods: ['POST']
        },
        'auth.login': { uri: 'api/auth/login', methods: ['POST'] },
        dashboard: { uri: 'dashboard', methods: ['GET', 'HEAD'] },
        'content-types.index': {
            uri: 'content-types',
            methods: ['GET', 'HEAD']
        },
        'content-types.create': {
            uri: 'content-types/create',
            methods: ['GET', 'HEAD']
        },
        'content-types.store': { uri: 'content-types', methods: ['POST'] },
        'content-types.edit': {
            uri: 'content-types/{contentTypeId}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['contentTypeId']
        },
        'content-types.update': {
            uri: 'content-types/{contentTypeId}',
            methods: ['PUT'],
            parameters: ['contentTypeId']
        },
        'content-types.destroy': {
            uri: 'content-types/{contentTypeId}',
            methods: ['DELETE'],
            parameters: ['contentTypeId']
        },
        'content-types.fields.store': {
            uri: 'content-types/{contentTypeId}/fields',
            methods: ['POST'],
            parameters: ['contentTypeId']
        },
        'content-types.fields.update': {
            uri: 'content-types/{contentTypeId}/fields/{fieldId}',
            methods: ['PUT'],
            parameters: ['contentTypeId', 'fieldId']
        },
        'content-types.fields.destroy': {
            uri: 'content-types/{contentTypeId}/fields/{fieldId}',
            methods: ['DELETE'],
            parameters: ['contentTypeId', 'fieldId']
        },
        'pages.update-content': {
            uri: 'pages/{pageId}/content',
            methods: ['POST', 'PUT'],
            parameters: ['pageId']
        },
        'pages.index': { uri: 'pages', methods: ['GET', 'HEAD'] },
        'pages.create': { uri: 'pages/create', methods: ['GET', 'HEAD'] },
        'pages.store': { uri: 'pages', methods: ['POST'] },
        'pages.edit': {
            uri: 'pages/{pageId}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['pageId']
        },
        'pages.update': {
            uri: 'pages/{pageId}',
            methods: ['PUT'],
            parameters: ['pageId']
        },
        'pages.destroy': {
            uri: 'pages/{pageId}',
            methods: ['DELETE'],
            parameters: ['pageId']
        },
        'content.categories.index': {
            uri: 'content/categories',
            methods: ['GET', 'HEAD']
        },
        'content.categories.create': {
            uri: 'content/categories/create',
            methods: ['GET', 'HEAD']
        },
        'content.categories.store': {
            uri: 'content/categories',
            methods: ['POST']
        },
        'content.categories.edit': {
            uri: 'content/categories/{categoryId}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['categoryId']
        },
        'content.categories.update': {
            uri: 'content/categories/{categoryId}',
            methods: ['PUT'],
            parameters: ['categoryId']
        },
        'content.categories.destroy': {
            uri: 'content/categories/{categoryId}',
            methods: ['DELETE'],
            parameters: ['categoryId']
        },
        'content.sections.posts.update-content': {
            uri: 'content/sections/{sectionId}/posts/{postId}/content',
            methods: ['POST', 'PUT'],
            parameters: ['sectionId', 'postId']
        },
        'content.sections.posts.index': {
            uri: 'content/sections/{sectionId}/posts',
            methods: ['GET', 'HEAD'],
            parameters: ['sectionId']
        },
        'content.sections.posts.create': {
            uri: 'content/sections/{sectionId}/posts/create',
            methods: ['GET', 'HEAD'],
            parameters: ['sectionId']
        },
        'content.sections.posts.store': {
            uri: 'content/sections/{sectionId}/posts',
            methods: ['POST'],
            parameters: ['sectionId']
        },
        'content.sections.posts.edit': {
            uri: 'content/sections/{sectionId}/posts/{postId}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['sectionId', 'postId']
        },
        'content.sections.posts.update': {
            uri: 'content/sections/{sectionId}/posts/{postId}',
            methods: ['PUT'],
            parameters: ['sectionId', 'postId']
        },
        'content.sections.posts.destroy': {
            uri: 'content/sections/{sectionId}/posts/{postId}',
            methods: ['DELETE'],
            parameters: ['sectionId', 'postId']
        },
        'content.sections.index': {
            uri: 'content/sections',
            methods: ['GET', 'HEAD']
        },
        'content.sections.create': {
            uri: 'content/sections/create',
            methods: ['GET', 'HEAD']
        },
        'content.sections.store': {
            uri: 'content/sections',
            methods: ['POST']
        },
        'content.sections.edit': {
            uri: 'content/sections/{sectionId}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['sectionId']
        },
        'content.sections.update': {
            uri: 'content/sections/{sectionId}',
            methods: ['PUT'],
            parameters: ['sectionId']
        },
        'content.sections.destroy': {
            uri: 'content/sections/{sectionId}',
            methods: ['DELETE'],
            parameters: ['sectionId']
        }
    }
}
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes)
}
export { Ziggy }
