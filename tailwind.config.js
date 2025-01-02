import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './database/fixtures/**/*.md',
    ],
    theme: {
        screens: {
            'sm': '576px',
            'md': '768px',
            'lg': '790px',
            'xl': '790px',
        },
        extend: {
            fontFamily: {
                'sans': ['Roboto', ...defaultTheme.fontFamily.sans],
                'condensed': ['Roboto Condensed', 'Roboto', ...defaultTheme.fontFamily.sans],
                'serif': ['Roboto Slab', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                'transparent': 'transparent',
                'transparent-black': 'rgba(0,0,0,.2)',
                'black': '#22292f',
                'white': '#ffffff',

                //colors with the names from zeplin
                'blue-light': '#88cdd9', // rgb(136, 205, 217)
                'blue': '#0fc0df', // rgb(15, 192, 223)
                'blue-90': 'rgba(15, 192, 223, 0.9)', // #0fc0df , 90%
                'blue-dark': '#0da5bf', // rgb(13, 165, 191)

                'grey': '#caced4', // rgb(202, 206, 212)
                'silver-two': '#c9cdd3', // rgb(201, 205, 211)

                'red-dark': '#e67e6a', // rgb(230, 126, 106)
                'red-light': '#ff8c76', // rgb(255, 140, 118)
                'red-light-90': 'rgba(255, 140, 118, 0.9)', // ff8c76 , 90%

                'bluey-grey': '#9dadc2', // rgb(157, 173, 194)
                'grey-lighter': '#edeff0', // rgb(237, 239, 240)
                'grey-darkest': '#37404d', // rgb(55, 64, 77)
                'grey-darker': '#4f5d6f', // rgb(79, 93, 111)
                'grey-darker-90': 'rgba(79, 93, 111, 0.9)', // #4f5d6f , 90%
                'grey-dark': '#6b7685', // rgb(107, 118, 133)

                'seaweed-green': '#309673', // rgb(48, 150, 115)
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}


// module.exports = {
//     purge: {
//         enabled: true,
//         content: [
//             './storage/framework/views/*.php',
//             './resources/**/*.blade.php',
//             './resources/**/*.js',
//             './resources/**/*.vue',
//         ],
//         safelist: [/language/, /hljs/, /code/, /blockquote/, /table/]
//     },
//     darkMode: false,
//     theme: {
//         colors: {
//             'transparent': 'transparent',
//             'transparent-black': 'rgba(0,0,0,.2)',
//             'black': '#22292f',
//             'white': '#ffffff',
//
//             //colors with the names from zeplin
//             'blue-light': '#88cdd9', // rgb(136, 205, 217)
//             'blue': '#0fc0df', // rgb(15, 192, 223)
//             'blue-90': 'rgba(15, 192, 223, 0.9)', // #0fc0df , 90%
//             'blue-dark': '#0da5bf', // rgb(13, 165, 191)
//
//             'grey': '#caced4', // rgb(202, 206, 212)
//             'silver-two': '#c9cdd3', // rgb(201, 205, 211)
//
//             'red-dark': '#e67e6a', // rgb(230, 126, 106)
//             'red-light': '#ff8c76', // rgb(255, 140, 118)
//             'red-light-90': 'rgba(255, 140, 118, 0.9)', // ff8c76 , 90%
//
//             'bluey-grey': '#9dadc2', // rgb(157, 173, 194)
//             'grey-lighter': '#edeff0', // rgb(237, 239, 240)
//             'grey-darkest': '#37404d', // rgb(55, 64, 77)
//             'grey-darker': '#4f5d6f', // rgb(79, 93, 111)
//             'grey-darker-90': 'rgba(79, 93, 111, 0.9)', // #4f5d6f , 90%
//             'grey-dark': '#6b7685', // rgb(107, 118, 133)
//
//             'seaweed-green': '#309673', // rgb(48, 150, 115)
//         },
//         screens: {
//             'sm': '576px',
//             'md': '768px',
//             'lg': '790px',
//             'xl': '790px',
//         },
//         fontFamily: {
//             'sans': [
//                 'Roboto',
//                 'system-ui',
//                 'BlinkMacSystemFont',
//                 '-apple-system',
//                 'Segoe UI',
//                 'Oxygen',
//                 'Ubuntu',
//                 'Cantarell',
//                 'Fira Sans',
//                 'Droid Sans',
//                 'Helvetica Neue',
//                 'sans-serif',
//             ],
//             'condensed': [
//                 'Roboto Condensed',
//                 'Roboto',
//                 'system-ui',
//                 'BlinkMacSystemFont',
//                 '-apple-system',
//                 'Segoe UI',
//                 'Oxygen',
//                 'Ubuntu',
//                 'Cantarell',
//                 'Fira Sans',
//                 'Droid Sans',
//                 'Helvetica Neue',
//                 'sans-serif',
//             ],
//             'serif': [
//                 'Roboto Slab',
//                 'Lucida Bright',
//                 'Lucidabright',
//                 'Lucida Serif',
//                 'Lucida',
//                 'DejaVu Serif',
//                 'Bitstream Vera Serif',
//                 'Liberation Serif',
//                 'Georgia',
//                 'serif',
//             ],
//             'mono': [
//                 'Menlo',
//                 'Monaco',
//                 'Consolas',
//                 'Liberation Mono',
//                 'Courier New',
//                 'monospace',
//             ]
//         },
//         fontSize: {
//             'xs': '.75rem',     // 12px
//             'sm': '.875rem',    // 14px
//             'base': '1rem',     // 16px
//             'lg': '1.125rem',   // 18px
//             'xl': '1.25rem',    // 20px
//             '2xl': '1.5rem',    // 24px
//             '3xl': '1.875rem',  // 30px
//             '4xl': '2.25rem',   // 36px
//             '5xl': '3rem',      // 48px
//         },
//         fontWeight: {
//             'thin': 200,
//             'light': 300,
//             'normal': 400,
//             'bold': 700,
//         },
//         lineHeight: {
//             'none': 1,
//             'tight': 1.25,
//             'normal': 1.5,
//             'loose': 2,
//             'xl': 3,
//         },
//         letterSpacing: {
//             'tight': '-0.05em',
//             'normal': '0',
//             'wide': '0.05em',
//             'xl': '0.1875em',
//         },
//         textColor: theme => theme('colors'),
//         backgroundColor: theme => theme('colors'),
//         borderWidth: {
//             default: '1px',
//             '0': '0',
//             '2': '2px',
//             '4': '4px',
//         },
//         borderColor: theme => ({
//             default: theme('colors.grey-dark'),
//             ...theme('colors')
//         }),
//         borderRadius: {
//             'none': '0',
//             'sm': '.125rem',
//             default: '.25rem',
//             'lg': '.5rem',
//             'full': '9999px',
//         },
//         width: {
//             'auto': 'auto',
//             'px': '1px',
//             '1': '0.25rem',
//             '2': '0.5rem',
//             '3': '0.75rem',
//             '4': '1rem',
//             '6': '1.5rem',
//             '7': '1.75rem',
//             '8': '2rem',
//             '10': '2.5rem',
//             '12': '3rem',
//             '16': '4rem',
//             '24': '6rem',
//             '32': '8rem',
//             '48': '12rem',
//             '64': '16rem',
//             '1/2': '50%',
//             '1/3': '33.33333%',
//             '2/3': '66.66667%',
//             '1/4': '25%',
//             '3/4': '75%',
//             '1/5': '20%',
//             '2/5': '40%',
//             '3/5': '60%',
//             '4/5': '80%',
//             '1/6': '16.66667%',
//             '5/6': '83.33333%',
//             'full': '100%',
//             'screen': '100vw'
//         },
//         height: {
//             'auto': 'auto',
//             'px': '1px',
//             '1': '0.25rem',
//             '2': '0.5rem',
//             '3': '0.75rem',
//             '4': '1rem',
//             '6': '1.5rem',
//             '7': '1.75rem',
//             '8': '2rem',
//             '10': '2.5rem',
//             '12': '3rem',
//             '16': '4rem',
//             '24': '6rem',
//             '32': '8rem',
//             '48': '12rem',
//             '64': '16rem',
//             'full': '100%',
//             'screen': '100vh'
//         },
//         minWidth: {
//             '0': '0',
//             'full': '100%',
//         },
//         minHeight: {
//             '0': '0',
//             'full': '100%',
//             'screen': '100vh'
//         },
//         maxWidth: {
//             'xs': '20rem',
//             'sm': '30rem',
//             'md': '40rem',
//             'lg': '50rem',
//             'xl': '60rem',
//             '2xl': '70rem',
//             '3xl': '80rem',
//             '4xl': '90rem',
//             '5xl': '100rem',
//             'full': '100%',
//         },
//         maxHeight: {
//             'full': '100%',
//             'screen': '100vh',
//         },
//         padding: {
//             'px': '1px',
//             '0': '0',
//             '1': '0.25rem',
//             '2': '0.5rem',
//             '3': '0.75rem',
//             '4': '1rem',
//             '6': '1.5rem',
//             '8': '2rem',
//             '10': '2.5rem',
//             '12': '3rem',
//             '16': '4rem',
//             '24': '6rem',
//             '32': '8rem',
//             '48': '12rem',
//         },
//         margin: {
//             'auto': 'auto',
//             'px': '1px',
//             '0': '0',
//             '1': '0.25rem',
//             '2': '0.4rem',
//             '3': '0.75rem',
//             '4': '1rem',
//             '6': '1.5rem',
//             '8': '2rem',
//             '-px': '-1px',
//             '-0': '-0',
//             '-1': '-0.25rem',
//             '-2': '-0.4rem',
//             '-3': '-0.75rem',
//             '-4': '-1rem',
//             '-6': '-1.5rem',
//             '-8': '-2rem',
//         },
//         boxShadow: {
//             default: '0 2px 4px 0 rgba(0,0,0,0.10)',
//             'md': '0 4px 8px 0 rgba(0,0,0,0.12), 0 2px 4px 0 rgba(0,0,0,0.08)',
//             'lg': '0 15px 30px 0 rgba(0,0,0,0.11), 0 5px 15px 0 rgba(0,0,0,0.08)',
//             'inner': 'inset 0 2px 4px 0 rgba(0,0,0,0.06)',
//             'none': 'none',
//         },
//         zIndex: {
//             'auto': 'auto',
//             '0': 0,
//             '10': 10,
//             '20': 20,
//             '30': 30,
//             '40': 40,
//             '50': 50,
//         },
//         fill: {
//             'current': 'currentColor',
//             'slate': '#C9CDD3',
//             'grey-dark': '#4f5d6f',
//         },
//         stroke: {
//             'current': 'currentColor',
//         },
//     },
//     variants: {
//         appearance: ['responsive'],
//         backgroundAttachment: ['responsive'],
//         backgroundColors: ['responsive', 'hover'],
//         backgroundPosition: ['responsive'],
//         backgroundRepeat: ['responsive'],
//         backgroundSize: ['responsive'],
//         borderColors: ['responsive', 'hover'],
//         borderRadius: ['responsive'],
//         borderStyle: ['responsive'],
//         borderWidths: ['responsive'],
//         cursor: ['responsive'],
//         display: ['responsive'],
//         flexDirection: ['responsive'],
//         flexWrap: ['responsive'],
//         alignItems: ['responsive'],
//         alignSelf: ['responsive'],
//         justifyContent: ['responsive'],
//         alignContent: ['responsive'],
//         flex: ['responsive'],
//         flexGrow: ['responsive'],
//         flexShrink: ['responsive'],
//         float: ['responsive'],
//         fonts: ['responsive'],
//         fontWeights: ['responsive', 'hover'],
//         height: ['responsive'],
//         leading: ['responsive'],
//         listStylePosition: ['responsive'],
//         listStyleType: ['responsive'],
//         margin: ['responsive'],
//         maxHeight: ['responsive'],
//         maxWidth: ['responsive'],
//         minHeight: ['responsive'],
//         minWidth: ['responsive'],
//         negativeMargin: ['responsive'],
//         opacity: ['responsive'],
//         overflow: ['responsive'],
//         padding: ['responsive'],
//         pointerEvents: ['responsive'],
//         position: ['responsive'],
//         inset: ['responsive'],
//         resize: ['responsive'],
//         shadows: ['responsive'],
//         svgFill: [],
//         svgStroke: [],
//         textAlign: ['responsive'],
//         textColors: ['responsive', 'hover'],
//         textSizes: ['responsive'],
//         fontStyle: ['responsive', 'hover'],
//         fontSmoothing: ['responsive', 'hover'],
//         textDecoration: ['responsive', 'hover'],
//         textTransform: ['responsive', 'hover'],
//         tracking: ['responsive'],
//         userSelect: ['responsive'],
//         verticalAlign: ['responsive'],
//         visibility: ['responsive'],
//         whitespace: ['responsive'],
//         wordBreak: ['responsive'],
//         width: ['responsive'],
//         zIndex: ['responsive'],
//     }
// };
