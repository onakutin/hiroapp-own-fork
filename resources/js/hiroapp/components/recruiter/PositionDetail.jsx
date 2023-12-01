import axios from "axios";
import React, { useContext, useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ApplicantList from "./ApplicantList";
import PositionDetailDetails from "./PositionDetailDetails";
import Context from "../../context/Context";
import DeletePosition from "../admin/DeletePosition";

const PositionDetail = () => {
    const { state } = useContext(Context);
    const [positionData, setPositionData] = useState([]);
    const { id } = useParams();
    const navigate = useNavigate();

    const fetchPositionDetail = async () => {
        try {
            const response = await axios.get(`/api/positions/${id}`);
            setPositionData(response.data);
        } catch (err) {
            console.log(err.response);
        }
    };

    useEffect(() => {
        fetchPositionDetail();
    }, []);

    return (
        <>
            <button onClick={() => navigate(-1)}>back</button>
            <div>
                <h2>Position Details</h2>
                {state.user.role_id === 1 && <DeletePosition />}
                <PositionDetailDetails position={positionData.position} />
            </div>
            <div>
                <h3>Applicants</h3>
                <div>
                    {positionData.applications?.length === 0 ? (
                        "No applicants yet"
                    ) : (
                        <ApplicantList applicants={positionData.applications} />
                    )}
                </div>
            </div>
        </>
    );
};

export default PositionDetail;
