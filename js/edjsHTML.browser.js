var edjsHTML = (function () {
  "use strict";
  var t = {
    delimiter: function () {
      return "<br/>";
    },
    header: function (t) {
      var e = t.data;
      return "<h" + e.level + "> " + e.text + " </h" + e.level + ">";
    },
    paragraph: function (t) {
      return "<p> " + t.data.text + " </p>";
    },
    list: function (t) {
      var e = t.data,
        n = "unordered" === e.style ? "ul" : "ol",
        r = "";
      return (
        e.items &&
          (r = e.items
            .map(function (t) {
              return "<li> " + t + " </li>";
            })
            .reduce(function (t, e) {
              return t + e;
            }, "")),
        "<" + n + "> " + r + " </" + n + ">"
      );
    },
    quote: function (t) {
      var e = t.data;
      return "<blockquote> " + e.text + " </blockquote> - " + e.caption;
    },
    embed: function (t) {
      var e = t.data;
      switch(e.service) {
        case 'youtube':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2 shadow' width='"+e.width+"' height='"+e.height+"' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
          break;
        case 'twitter':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2' width='"+e.width+"' height='"+(e.height)*2+"' style='margin: 0 auto;' scrolling='no' frameborder='0' allowtransparency='true' allowfullscreen='true'></iframe>";
          break;
        case 'instagram':
          return "<iframe src='"+e.embed+"' class='mx-auto d-block col mb-2 shadow' width='"+e.width+"' height='"+e.height+"'></iframe>";
          break;
        case 'drena':
          return "<iframe width='100%' src='"+e.embed+"' scrolling='no' allowfullscreen='true'></iframe>";
          break;
        default:
          return "<a href='"+e.source+"'>"+e.source+"</a>";
          break;
      }
    },
  };
  function e(t) {
    return new Error(
      'O tipo: "'+ t +'" não existe, contacta um administrador.<br>'
    );
  }
  return function (n) {
    return (
      void 0 === n && (n = {}),
      Object.assign(t, n),
      {
        parse: function (n) {
          return n.blocks.map(function (n) {
            return t[n.type] ? t[n.type](n) : e(n.type);
          });
        },
        parseBlock: function (n) {
          return t[n.type] ? t[n.type](n) : e(n.type);
        },
      }
    );
  };
})();
