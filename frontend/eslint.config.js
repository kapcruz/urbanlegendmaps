import js from "@eslint/js";
import globals from "globals";
import pluginVue from "eslint-plugin-vue";
import { defineConfig } from "eslint/config";
import prettier from "eslint-config-prettier";

export default defineConfig([
  {
    files: ["**/*.{js,vue}"],
    ignores: ["dist", "node_modules"],
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.es2021,
      },
    },
    extends: [js.configs.recommended, ...pluginVue.configs["flat/essential"], prettier],
    rules: {
      "vue/multi-word-component-names": [
        "error",
        {
          ignores: ["App.vue"],
        },
      ],
    },
  },
]);
