import { defineConfig } from 'vite'
import uni from '@dcloudio/vite-plugin-uni'
import UniKuRoot from '@uni-ku/root'
import tailwindcss from '@tailwindcss/postcss'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { UnifiedViteWeappTailwindcssPlugin } from 'weapp-tailwindcss/vite'

const isH5: boolean = process.env.UNI_PLATFORM === 'h5'
const isApp: boolean = process.env.UNI_PLATFORM === 'app'
const WeappTailwindcssDisabled: boolean = isH5 || isApp

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        UniKuRoot(),
        uni(),
        UnifiedViteWeappTailwindcssPlugin(
            {
                rem2rpx: true,
                disabled: WeappTailwindcssDisabled,
                cssRemoveProperty: true,
                cssEntries: [
                    '@/assets/styles/index.scss'
                ]
            }
        ),
        AutoImport({
            imports: [
                'vue',
                'pinia',
                {
                    '@dcloudio/uni-app': [
                        'onLaunch',
                        'onLoad',
                        'onShow',
                        'onHide'
                    ]
                }
            ],
            dts: 'auto-imports.d.ts',
            eslintrc: {
                enabled: true
            }
        }),
        Components({
            dirs: ['src/components'],
            extensions: ['vue'],
            dts: 'components.d.ts',
            prefix: 'W'
        })
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss()
            ]
        },
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
                silenceDeprecations: ['legacy-js-api']
            }
        }
    }
})