require('./bootstrap');
import React from 'react';
import { render } from 'react-dom';
import { Router, Route, browserHistory } from 'react-router';
import PropTypes from 'prop-types'
import Master from './components/User/MasterUser';
import CreateUser from './components/User/CreateUser';
import DisplayUser from './components/User/DisplayUser';
import UpdateUser from './components/User/UpdateUser';
render(
  <Router history={browserHistory}>
      <Route path="/" component={Master} >
        <Route path="/add-item" component={CreateUser} />
        <Route path="/display-item" component={DisplayUser} />
        <Route path="/edit/:id" component={UpdateUser} />
      </Route>
    </Router>,
document.getElementById('crud-app'));