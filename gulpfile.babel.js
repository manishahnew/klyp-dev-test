import gulp from 'gulp';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass( dartSass );
import cleanCSS from 'gulp-clean-css';
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import del from 'del';

const paths = {
    styles: {
        src: ['src/assets/scss/style.scss'],
        dest: 'dist/assets/css'
    },
    scripts: {
        src: ['src/assets/js/scripts.js'],
        dest: 'dist/assets/js'
    }
}

export const styles = (done) => {
    return gulp.src(paths.styles.src)
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS({compatibility:'ie8'}))
        .pipe(gulp.dest(paths.styles.dest))
}

export const scripts = () => {
    return gulp.src(paths.scripts.src)
        .pipe(named())
        .pipe(webpack({
            module: {
                rules: [
                    {
                        test: /\.js$/,
                        use: {
                            loader: 'babel-loader',
                            options: {
                                presets: ['@babel/preset-env']
                            }
                        }
                    }
                ]
            },
            output: {
                filename: '[name].js'
            },
            devtool: false,
            mode: 'production'
        }))
        .pipe(gulp.dest(paths.scripts.dest))
}

export const clean = () => del(['dist']);

export const build = gulp.series(clean, gulp.parallel(styles, scripts));