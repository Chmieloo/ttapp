// Compiled using marko@4.15.4 - DO NOT EDIT
"use strict";

var marko_template = module.exports = require("marko/src/html").t(__filename),
    marko_componentType = "/front$1.0.0/src/pages/index/index-template.marko",
    components_helpers = require("marko/src/components/helpers"),
    marko_renderer = components_helpers.r,
    marko_defineComponent = components_helpers.c,
    marko_loadTemplate = require("marko/src/runtime/helper-loadTemplate"),
    Layout = marko_loadTemplate(require.resolve("../layout.marko")),
    index_template = marko_loadTemplate(require.resolve("./components")),
    marko_helpers = require("marko/src/runtime/html/helpers"),
    marko_loadTag = marko_helpers.t,
    index_tag = marko_loadTag(index_template),
    marko_dynamicTag = marko_helpers.d;

function render(input, out, __component, component, state) {
  var data = input;

  marko_dynamicTag(Layout, {
      body: {
          renderBody: function renderBody(out) {
            index_tag({}, out, __component, "2");
          }
        }
    }, null, out, __component, "0");
}

marko_template._ = marko_renderer(render, {
    ___implicit: true,
    ___type: marko_componentType
  });

marko_template.Component = marko_defineComponent({}, marko_template._);

marko_template.meta = {
    id: "/front$1.0.0/src/pages/index/index-template.marko",
    tags: [
      "../layout.marko",
      "./components"
    ]
  };
