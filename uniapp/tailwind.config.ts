/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './index.html',
        './src/**/*.{html,js,ts,jsx,tsx,vue}'
    ],
    theme: {
        colors: {
            black: 'var(--color-black, #000000)',
            white: 'var(--color-white, #ffffff)',
            transparent: 'var(--color-transparent, transparent)',

            overlay: 'var(--bg-color-overlay, #ffffff)',
            lighter: 'var(--bg-color-lighter, #f0f0f0)',
            light: 'var(--bg-color-light, #e0e0e0)',
            page: 'var(--bg-color-page, #ffffff)',
            body: 'var(--bg-color, #f6f6f6)',
            tx: {
                primary: 'var(--text-color-primary, #303133)',
                regular: 'var(--text-color-regular, #606266)',
                secondary: 'var(--text-color-secondary, #909193)',
                placeholder: 'var(--text-color-placeholder, #c0c4cc)',
                disabled: 'var(--text-color-disabled, #d9d9d9)',
                thinnest: 'var(--text-color-thinnest, #e8e8e8)'
            },
            br: {
                DEFAULT: 'var(--border-color)',
                light: 'var(--border-color-light)',
                lighter: 'var(--border-color-lighter)',
                thinned: 'var(--border-color-thinned)'
            },
            primary: {
                DEFAULT: 'var(--color-primary, #3C5EFD)',
                'dark-2': 'var(--color-primary-dark-2, #3e66c0)',
                'light-3': 'var(--color-primary-light-3, #82a6f5)',
                'light-5': 'var(--color-primary-light-5, #a6c0f8)',
                'light-7': 'var(--color-primary-light-7, #cad9fb)',
                'light-8': 'var(--color-primary-light-7, #dbe6fc)',
                'light-9': 'var(--color-primary-light-9, #edf2fe)'
            },
            success: {
                DEFAULT: 'var(--color-success, #34d19d)',
                'dark-2': 'var(--color-success-dark-2, #2aa77e)',
                'light-3': 'var(--color-success-light-3, #71dfba)',
                'light-5': 'var(--color-success-light-5, #9ae8ce)',
                'light-7': 'var(--color-success-light-7, #c2f1e2)',
                'light-8': 'var(--color-success-light-9, #d6f6eb)',
                'light-9': 'var(--color-success-light-8, #ebfaf5)'
            },
            warning: {
                DEFAULT: 'var(--color-warning, #f0883a)',
                'dark-2': 'var(--color-warning-dark-2, #c06d2e)',
                'light-3': 'var(--color-warning-light-3, #f5ac75)',
                'light-5': 'var(--color-warning-light-5, #f8c49d)',
                'light-7': 'var(--color-warning-light-7, #fbdbc4)',
                'light-8': 'var(--color-warning-light-7, #fce7d8)',
                'light-9': 'var(--color-warning-light-9, #fef3eb)'
            },
            danger: {
                DEFAULT: 'var(--color-danger, #fa4350)',
                'dark-2': 'var(--color-danger-dark-2, #c83640)',
                'light-3': 'var(--color-danger-light-3, #fc7b85)',
                'light-5': 'var(--color-danger-light-5, #fda1a8)',
                'light-7': 'var(--color-danger-light-7, #fec7cb)',
                'light-8': 'var(--color-danger-light-7, #fed9dc)',
                'light-9': 'var(--color-danger-light-9, #ffecee)'
            }
        },
        fontSize: {
            xs: '24rpx',
            sm: '26rpx',
            base: '28rpx',
            lg: '30rpx',
            xl: '32rpx',
            '2xl': '34rpx',
            '3xl': '36rpx',
            '4xl': '40rpx',
            '5xl': '44rpx',
            '6xl': '50rpx',
            '7xl': '58rpx',
            '8xl': '70rpx',
            '9xl': '96rpx'
        },
        fontFamily: {
            sans: [
                'Source Han Sans CN',
                'Helvetica Neue',
                'Arial',
                'sans-serif'
            ]
        }
    },
    plugins: []
}