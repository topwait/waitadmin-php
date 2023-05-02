module.exports = {
    'root': true,
    'ignorePatterns': ['src/uni_modules/'],
    'env': {
        'browser': true,
        'es2021': true,
        'node': true
    },
    'extends': [
        'eslint:recommended',
        'plugin:vue/vue3-recommended'
    ],
    'globals': {
        'uni': 'readonly',
        'plus': 'readonly',
        'wx': 'readonly',
    },
    'parserOptions': {
        'ecmaVersion': 'latest',
        'sourceType': 'module'
    },
    'plugins': [
        'vue'
    ],
    'rules': {
        'semi': ['warn', 'never'],
        'no-var': 'warn',
        'no-empty': 'warn',
        'no-shadow': 'off',
        'no-console': 'warn',
        'no-debugger': 'warn',
        'no-lone-blocks': 'warn',
        'no-extra-parens': 'warn',
        'no-multi-spaces': 'off',
        'no-duplicate-case': 'warn',
        'no-empty-function': 'warn',
        'no-redeclare': 'warn',
        'no-func-assign': 'warn',
        'no-unreachable': 'warn',
        'no-else-return': 'warn',
        'no-return-assign': 'warn',
        'no-return-await': 'warn',
        'no-self-compare': 'warn',
        'no-useless-catch': 'warn',
        'no-useless-return': 'warn',
        'no-mixed-spaces-and-tabs': 'warn',
        'no-multiple-empty-lines': 'warn',
        'no-trailing-spaces': 'warn',
        'no-useless-call': 'warn',
        'no-delete-var': 'off',

        'dot-notation': 'warn',
        'default-case': 'warn',
        'eqeqeq': 'warn',
        'curly': 'warn',
        'space-before-blocks': 'warn',
        'space-in-parens': 'warn',
        'space-infix-ops': 'warn',
        'space-unary-ops': 'warn',
        'switch-colon-spacing': 'warn',
        'arrow-spacing': 'warn',
        'array-bracket-spacing': 'warn',
        'brace-style': 'warn',
        'camelcase': 'off',
        'indent': ['warn', 4],
        'max-depth': ['warn', 4],
        'max-statements': ['warn', 100],
        'max-nested-callbacks': ['warn', 3],
        'max-statements-per-line': ['warn', { max: 1 }],
        'quotes': ['warn', 'single', 'avoid-escape'],

        'vue/require-default-prop': 0,
        'vue/multi-word-component-names': 0,
        'vue/singleline-html-element-content-newline': 0,
        'vue/multiline-html-element-content-newline': 0,
        'vue/max-attributes-per-line': ['warn', { singleline: 5 }],
        'vue/html-indent': ['warn', 4, {
            'attribute': 1,
            'baseIndent': 1,
            'closeBracket': 0,
            'alignAttributesVertically': true,
            'ignores': []
        }],
        'vue/html-self-closing': ['error', {
            'html': {
                'void': 'always',
                'normal': 'never',
                'component': 'always'
            },
            'svg': 'always',
            'math': 'always'
        }]
    }
}
