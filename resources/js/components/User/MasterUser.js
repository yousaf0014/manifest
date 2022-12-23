import React, {Component} from 'react';
import { Router, Route, Link } from 'react-router';
class Master extends Component {
  render(){
    return (
      <div className="container">
        <nav className="navbar navbar-default">
          <div className="container-fluid">
            <div className="navbar-header">
              <a className="navbar-brand" href="#">Manifest</a>
            </div>
            <ul className="nav navbar-nav">
              <li><Link to="/">Home</Link></li>
              <li><Link to="add-item">Create User</Link></li>
              <li><Link to="display-item">Users</Link></li>
            </ul>
          </div>
        </nav>
        <div>
          {this.props.children}
        </div>
      </div>
    )
  }
}
export default Master;