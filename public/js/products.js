/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/products.js ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Products: () => (/* binding */ Products)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var Products = /*#__PURE__*/function () {
  function Products() {
    _classCallCheck(this, Products);
    this.Products = [];
  }
  return _createClass(Products, null, [{
    key: "makeAuthenticatedRequest",
    value: function makeAuthenticatedRequest(url, method) {
      var data = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
      var successFunction = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function () {
        Products.getUserProducts();
      };
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      return new Promise(function (resolve, reject) {
        $.ajax({
          url: url,
          method: method,
          data: JSON.stringify(data),
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          xhrFields: {
            withCredentials: true
          },
          contentType: 'application/json; charset=utf-8',
          success: function success(data) {
            resolve(data);
            successFunction();
          },
          error: function error(xhr, status, _error) {
            console.error('Error while making request: ', _error);
            reject(_error);
          }
        });
      });
    }
  }, {
    key: "getProducts",
    value: function getProducts() {
      return new Promise(function (resolve, reject) {
        $.ajax({
          url: '/api/Product',
          method: 'GET',
          dataType: 'json',
          success: function success(data) {
            resolve(data); // Resolve the promise with the Product data
          },
          error: function error(xhr, status, _error2) {
            console.error('Error fetching Products:', _error2);
            reject(_error2); // Reject the promise with the error
          }
        });
      });
    }
  }, {
    key: "getUserProducts",
    value: function getUserProducts(type) {
      return Products.makeAuthenticatedRequest('/api/user/' + type, "GET", {}, function () {});
    }
  }, {
    key: "removeUserFromProduct",
    value: function removeUserFromProduct(ProductId, successFunction) {
      return Products.makeAuthenticatedRequest('/api/user/Product', 'DELETE', {
        ProductId: ProductId
      }, successFunction);
    }
  }]);
}();
/******/ })()
;