  import typescript from '@rollup/plugin-typescript';

  export default {
    input: 'src/ts/Game.ts',
    output: {
      dir: 'modules/js',
      format: 'es',
      sourcemap: false,
      preserveModules: true,
      preserveModulesRoot: 'src/ts',
    },
    plugins: [
      typescript({
        tsconfig: './tsconfig.json',
        outDir: 'modules/js',
      }),
    ],
    treeshake: false,
  };