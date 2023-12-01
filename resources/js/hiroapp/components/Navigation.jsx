import React, { useContext } from "react";
import Context from "../context/Context";
import { Link } from "react-router-dom";

const Navigation = () => {
    const { state } = useContext(Context);
    return (
        <>
            <nav className="Navigation">
                {state.user?.role_id === 1 && (
                    <>
                        <Link to="/">Home</Link>
                        <Link to="/users/create">Create New User</Link>
                    </>
                )}
                {state.user?.role_id === 2 && (
                    <>
                        <Link to="/">Home</Link>
                        <Link to="/applications">Your Applications</Link>
                        <Link to="/positions">Positions</Link>
                    </>
                )}
                {state.user?.role_id === 3 && (
                    <>
                        <Link to="/">Home</Link>
                        <Link to="/position/create">Create New Position</Link>
                    </>
                )}
            </nav>
        </>
    );
};

export default Navigation;
