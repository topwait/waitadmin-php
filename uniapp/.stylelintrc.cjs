/* global module */
module.exports = {
    customSyntax: 'postcss-html',
    extends: [
        'stylelint-config-standard',
        'stylelint-config-standard-vue',
        'stylelint-config-standard-scss',
        'stylelint-config-recess-order'
    ],
    rules: {
        'color-hex-length': 'long',
        'selector-max-id': 1,
        'max-nesting-depth': 5,
        'selector-max-class': 5,
        'block-no-empty': null,
        'no-empty-source': null,
        'import-notation': null,
        'property-no-unknown': null,
        'selector-not-notation': null,
        'keyframes-name-pattern': null,
        'selector-class-pattern': null,
        'rule-empty-line-before': null,
        'custom-property-pattern': null,
        'declaration-no-important': null,
        'no-descending-specificity': null,
        'declaration-empty-line-before': null,
        'custom-property-empty-line-before': null,
        'custom-property-no-missing-var-function': null,
        'declaration-block-single-line-max-declarations': 30,
        'selector-type-no-unknown': [true, {
            'ignoreTypes': ['page']
        }],

        'selector-pseudo-class-no-unknown': [
            true,
            {
                ignorePseudoClasses: [
                    'deep',
                    'global',
                    'export'
                ]
            }
        ],

        'selector-pseudo-element-no-unknown': [
            true,
            {
                ignorePseudoElements: [
                    'v-deep',
                    'v-global',
                    'v-slotted',
                    'slotted'
                ]
            }
        ],

        'unit-no-unknown': [
            true,
            {
                ignoreUnits: [
                    'rpx',
                    'upx'
                ]
            }
        ]
    },

    ignoreFiles: [
        '**/*.js',
        '**/*.jsx',
        '**/*.tsx',
        '**/*.ts',
        'dist/**',
        'node_modules/**',
        'unpackage/**',
        '**/static/**',
        '**/hybrid/**'
    ]
}
