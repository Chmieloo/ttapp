// Compiled using marko@4.15.4 - DO NOT EDIT
"use strict";

var marko_template = module.exports = require("marko/src/html").t(__filename),
    marko_componentType = "/front$1.0.0/src/pages/layout.marko",
    components_helpers = require("marko/src/components/helpers"),
    marko_renderer = components_helpers.r,
    marko_defineComponent = components_helpers.c,
    ___browser_json = require.resolve("../browser.json"),
    marko_helpers = require("marko/src/runtime/html/helpers"),
    marko_loadTag = marko_helpers.t,
    lasso_page_tag = marko_loadTag(require("@lasso/marko-taglib/taglib/config-tag")),
    lasso_head_tag = marko_loadTag(require("@lasso/marko-taglib/taglib/head-tag")),
    component_globals_tag = marko_loadTag(require("marko/src/components/taglib/component-globals-tag")),
    marko_loadTemplate = require("marko/src/runtime/helper-loadTemplate"),
    top_menu_template = marko_loadTemplate(require.resolve("./components/top-menu/top-menu.marko")),
    top_menu_tag = marko_loadTag(top_menu_template),
    lasso_body_tag = marko_loadTag(require("@lasso/marko-taglib/taglib/body-tag")),
    browser_refresh_tag = marko_loadTag(require("browser-refresh-taglib/refresh-tag")),
    init_components_tag = marko_loadTag(require("marko/src/components/taglib/init-components-tag")),
    await_reorderer_tag = marko_loadTag(require("marko/src/taglibs/core/await/reorderer-renderer"));

function render(input, out, __component, component, state) {
  var data = input;

  lasso_page_tag({
      packagePath: ___browser_json,
      dirname: __dirname,
      filename: __filename
    }, out);

  out.w("<!doctype html><html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><title>ttapp</title>");

  lasso_head_tag({}, out, __component, "5");

  lasso_head_tag({}, out, __component, "6");

  out.w("</head><body>");

  component_globals_tag({}, out);

  top_menu_tag({}, out, __component, "8");

  lasso_body_tag({}, out, __component, "9");

  browser_refresh_tag({}, out, __component, "10");

  lasso_body_tag({}, out, __component, "11");

  init_components_tag({}, out);

  await_reorderer_tag({}, out, __component, "12");

  out.w("</body></html>");
}

marko_template._ = marko_renderer(render, {
    ___implicit: true,
    ___type: marko_componentType
  });

marko_template.Component = marko_defineComponent({}, marko_template._);

marko_template.meta = {
    id: "/front$1.0.0/src/pages/layout.marko",
    tags: [
      "@lasso/marko-taglib/taglib/config-tag",
      "@lasso/marko-taglib/taglib/head-tag",
      "marko/src/components/taglib/component-globals-tag",
      "./components/top-menu/top-menu.marko",
      "@lasso/marko-taglib/taglib/body-tag",
      "browser-refresh-taglib/refresh-tag",
      "marko/src/components/taglib/init-components-tag",
      "marko/src/taglibs/core/await/reorderer-renderer"
    ]
  };
