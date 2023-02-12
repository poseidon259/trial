import React from "react";
import ReactDOM from "react-dom/client";
import "./index.scss";
import { Button, Space } from "antd";

const App = () => (
    <Space wrap>
        <Button type="primary">Primary Button</Button>
        <Button>Default Button</Button>
        <Button type="dashed">Dashed Button</Button>
        <Button type="text">Text Button</Button>
        <Button type="link">Link Button</Button>
    </Space>
);
export default App;

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
