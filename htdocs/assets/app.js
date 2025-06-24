/* CSS */
import './styles/variables.css';
import './styles/app.css';

import './styles/login/index.css';

import './styles/home/navigation.css';

import './styles/home/product-list.css';
import './styles/home/product-box.css';

import './styles/home/tab-list.css';
import './styles/home/tab-box.css';

import './styles/home/popup.css';
import './styles/home/loader.css';

import './styles/home/cart-content.css';

import './styles/home/gpt.css';

/* JS */
import Login from "./JS/login.js";
import PopUp from "./JS/popup.js";
import AddToCart from "./JS/addToCart.js";

new Login();
new PopUp();
new AddToCart();
