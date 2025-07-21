const express = require("express");
const mongoose = require("mongoose");
const cors = require("cors");

const app = express();
app.use(express.json());
app.use(cors());

mongoose.connect("mongodb://localhost:27017/userDB", {
    useNewUrlParser: true,
    useUnifiedTopology: true,
});

const UserSchema = new mongoose.Schema({
    email: String,
    number: String,
});

const User = mongoose.model("User", UserSchema);

// API to get user data by ID
app.get("/api/user/:id", async (req, res) => {
    try {
        const user = await User.findById(req.params.id);
        if (!user) return res.status(404).json({ message: "User not found" });
        res.json(user);
    } catch (error) {
        res.status(500).json({ message: "Server error" });
    }
});

// API to create a new user (for testing)
app.post("/api/user", async (req, res) => {
    const { email, number } = req.body;
    const newUser = new User({ email, number });
    await newUser.save();
    res.json(newUser);
});

app.listen(5000, () => console.log("Server running on port 5000"));
