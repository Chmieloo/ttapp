// Compiled using marko@4.15.4 - DO NOT EDIT
"use strict";

var marko_template = module.exports = require("marko/src/html").t(__filename),
    marko_component = {},
    marko_componentType = "/front$1.0.0/src/pages/components/top-menu/top-menu.marko",
    components_helpers = require("marko/src/components/helpers"),
    marko_renderer = components_helpers.r,
    marko_defineComponent = components_helpers.c;

function render(input, out, __component, component, state) {
  var data = input;

  out.w("<div class=\"topMenu\"><div><a href=\"#/\" class=\"router-link-exact-active router-link-active\">ttapp</a></div><div><a href=\"#/player/list\">players</a></div><div><a href=\"#/tournament/1/standings\">standings</a></div><div><a href=\"#/tournament/list\">tournaments</a></div><div><a href=\"#/tournament/match/list\">tournament schedule</a></div><div><a href=\"#/playoffs/match/list\">playoffs</a></div></div>");
}

marko_template._ = marko_renderer(render, {
    ___type: marko_componentType
  }, marko_component);

marko_template.Component = marko_defineComponent(marko_component, marko_template._);

marko_template.meta = {
    deps: [
      {
          type: "less",
          code: ".topMenu {\r\n    background-color: #3e3e3e;\r\n    clear: both;\r\n    height: 50px;\r\n    line-height: 50px;\r\n    padding-left: 15px;\r\n    font-weight: 600;\r\n    box-shadow: 0px 4px 4px black;\r\n  }\r\n\r\n  .topMenu div {\r\n    float: left;\r\n    margin-right: 40px;\r\n    text-transform: uppercase;\r\n  }\r\n\r\n  .topMenu div:first-child a {\r\n    color: white;\r\n  }\r\n\r\n  a {\r\n    color: #9e9e9e;\r\n    text-decoration: none;\r\n  }\r\n\r\n  a:hover {\r\n    color: white;\r\n  }",
          virtualPath: "./top-menu.marko.less",
          path: "./top-menu.marko"
        }
    ],
    id: "/front$1.0.0/src/pages/components/top-menu/top-menu.marko",
    component: "./top-menu.marko"
  };
