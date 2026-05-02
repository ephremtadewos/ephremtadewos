import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import javax.swing.border.EmptyBorder;
import javax.swing.border.LineBorder;
import javax.swing.text.AbstractDocument;
import javax.swing.text.AttributeSet;
import javax.swing.text.DocumentFilter;
import java.awt.*;
import java.awt.event.*;
import java.sql.*;
import java.util.Random;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.regex.Pattern;
import javax.swing.text.BadLocationException;
public class ElectricityBillingSystem extends JFrame {
    private static final String DB_URL = "jdbc:mysql://localhost:3306/electricity_billing";
    private static final String DB_USER = "root";
    private static final String DB_PASS = "";
    
    private Connection conn;
    private CardLayout cardLayout;
    private JPanel mainPanel;
    private String currentUser = null;
    private String currentRole = null;
    private String currentMeterNum = null;
    
    private final Color primaryColor = new Color(0, 102, 204);
    private final Color secondaryColor = new Color(51, 51, 51);
    private final Color accentColor = new Color(255, 193, 7);
    private final Color lightBg = new Color(245, 245, 245);
    private final Color white = Color.WHITE;
    private final Color dangerColor = new Color(220, 53, 69);
    private final Color successColor = new Color(40, 167, 69);
    
    private final Font titleFont = new Font("Segoe UI", Font.BOLD, 26);
    private final Font headerFont = new Font("Segoe UI", Font.BOLD, 16);
    private final Font normalFont = new Font("Segoe UI", Font.PLAIN, 14);
    
    public ElectricityBillingSystem() {
        initDB();
        initGUI();
    }
    
    private void initDB() {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS);
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM users WHERE role='admin'");
            if (rs.next() && rs.getInt(1) == 0) {
                stmt.execute("INSERT INTO users (full_name, meter_num, country, region, zone, woreda, kebele, username, password, role) VALUES ('Administrator', 'ADMIN001', 'Ethiopia', 'Addis Ababa', 'Central', 'Addis Ketema', '01', 'admin', 'admin123', 'admin')");
            }
            rs = stmt.executeQuery("SELECT COUNT(*) FROM tariff");
            if (rs.next() && rs.getInt(1) == 0) {
                stmt.execute("INSERT INTO tariff (min_kwh, max_kwh, price_per_kwh, effective_date) VALUES (0, 50, 0.50, '2024-01-01')");
                stmt.execute("INSERT INTO tariff (min_kwh, max_kwh, price_per_kwh, effective_date) VALUES (51, 100, 0.70, '2024-01-01')");
                stmt.execute("INSERT INTO tariff (min_kwh, max_kwh, price_per_kwh, effective_date) VALUES (101, 200, 0.90, '2024-01-01')");
                stmt.execute("INSERT INTO tariff (min_kwh, max_kwh, price_per_kwh, effective_date) VALUES (201, NULL, 1.20, '2024-01-01')");
            }
        } catch (ClassNotFoundException e) {
            JOptionPane.showMessageDialog(null, "MySQL JDBC Driver not found!\nPlease add mysql-connector-java.jar to classpath.", "Driver Error", JOptionPane.ERROR_MESSAGE);
            System.exit(1);
        } catch (SQLException e) {
            JOptionPane.showMessageDialog(null, "Database Connection Failed!\nError: " + e.getMessage() + "\n\nPlease ensure:\n1. MySQL is running\n2. Database 'electricity_billing' exists\n3. Run electricity_billing.sql first", "Database Error", JOptionPane.ERROR_MESSAGE);
            System.exit(1);
        }
    }
    
    private void initGUI() {
        setTitle("⚡ Electricity Billing System - Ethiopia");
        setSize(1100, 680);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setResizable(false);
        
        cardLayout = new CardLayout();
        mainPanel = new JPanel(cardLayout);
        
        mainPanel.add(createLoginPanel(), "LOGIN");
        mainPanel.add(createAdminPanel(), "ADMIN");
        mainPanel.add(createUserPanel(), "USER");
        
        add(mainPanel);
        cardLayout.show(mainPanel, "LOGIN");
    }
    
    private JPanel createLoginPanel() {
        JPanel panel = new JPanel(new GridBagLayout());
        panel.setBackground(lightBg);
        panel.setBorder(new EmptyBorder(30, 30, 30, 30));
        
        JPanel card = new JPanel(new BorderLayout(0, 20));
        card.setBackground(white);
        card.setPreferredSize(new Dimension(400, 400));
        card.setBorder(BorderFactory.createCompoundBorder(
            new LineBorder(primaryColor, 2, true),
            new EmptyBorder(30, 35, 30, 35)));
        
        JLabel titleLabel = new JLabel("⚡ Electricity Billing");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        titleLabel.setHorizontalAlignment(SwingConstants.CENTER);
        
        JLabel subtitleLabel = new JLabel("Federal Democratic Republic of Ethiopia");
        subtitleLabel.setFont(normalFont);
        subtitleLabel.setForeground(secondaryColor);
        subtitleLabel.setHorizontalAlignment(SwingConstants.CENTER);
        
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(white);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 0, 10, 0);
        gbc.fill = GridBagConstraints.HORIZONTAL;
        gbc.gridwidth = 2;
        
        JTextField userField = createStyledTextField(20);
        JPasswordField passField = createStyledPasswordField(20);
        
        gbc.gridx = 0; gbc.gridy = 0;
        formPanel.add(createFieldLabel("Username"), gbc);
        gbc.gridy = 1;
        formPanel.add(userField, gbc);
        gbc.gridy = 2;
        formPanel.add(createFieldLabel("Password"), gbc);
        gbc.gridy = 3;
        formPanel.add(passField, gbc);
        
        JButton loginBtn = createStyledButton("LOGIN", primaryColor, white);
        loginBtn.setPreferredSize(new Dimension(200, 42));
        loginBtn.setFont(new Font("Segoe UI", Font.BOLD, 15));
        
        loginBtn.addActionListener(e -> {
            String username = userField.getText().trim();
            String password = new String(passField.getPassword());
            if (authenticate(username, password)) {
                userField.setText("");
                passField.setText("");
                if (currentRole.equals("admin")) {
                    cardLayout.show(mainPanel, "ADMIN");
                } else {
                    cardLayout.show(mainPanel, "USER");
                }
            }
        });
        
        passField.addActionListener(e -> loginBtn.doClick());
        
        gbc.gridy = 4;
        gbc.insets = new Insets(20, 0, 0, 0);
        formPanel.add(loginBtn, gbc);
        
        card.add(titleLabel, BorderLayout.NORTH);
        card.add(formPanel, BorderLayout.CENTER);
        card.add(subtitleLabel, BorderLayout.SOUTH);
        
        panel.add(card);
        return panel;
    }
    
    private JLabel createFieldLabel(String text) {
        JLabel label = new JLabel(text);
        label.setFont(normalFont);
        label.setForeground(secondaryColor);
        return label;
    }
    
    private JTextField createStyledTextField(int cols) {
        JTextField field = new JTextField(cols);
        field.setFont(normalFont);
        field.setBorder(BorderFactory.createCompoundBorder(
            new LineBorder(new Color(180, 180, 180)),
            new EmptyBorder(10, 12, 10, 12)));
        return field;
    }
    
    private JPasswordField createStyledPasswordField(int cols) {
        JPasswordField field = new JPasswordField(cols);
        field.setFont(normalFont);
        field.setBorder(BorderFactory.createCompoundBorder(
            new LineBorder(new Color(180, 180, 180)),
            new EmptyBorder(10, 12, 10, 12)));
        return field;
    }
    
    private boolean authenticate(String username, String password) {
        if (username.isEmpty() || password.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Please enter username and password!", "Login Error", JOptionPane.WARNING_MESSAGE);
            return false;
        }
        try {
            PreparedStatement ps = conn.prepareStatement("SELECT * FROM users WHERE username=? AND password=? AND status='active'");
            ps.setString(1, username);
            ps.setString(2, password);
            ResultSet rs = ps.executeQuery();
            
            if (rs.next()) {
                currentUser = rs.getString("full_name");
                currentRole = rs.getString("role");
                currentMeterNum = rs.getString("meter_num");
                return true;
            } else {
                JOptionPane.showMessageDialog(this, "Invalid username or password!", "Login Error", JOptionPane.ERROR_MESSAGE);
                return false;
            }
        } catch (SQLException ex) {
            JOptionPane.showMessageDialog(this, "Database Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
            return false;
        }
    }
    
    private JPanel createAdminPanel() {
        JPanel panel = new JPanel(new BorderLayout());
        panel.setBackground(lightBg);
        
        JPanel header = createHeader("Admin Dashboard", primaryColor);
        panel.add(header, BorderLayout.NORTH);
        
        JPanel content = new JPanel(new GridBagLayout());
        content.setBackground(lightBg);
        content.setBorder(new EmptyBorder(30, 30, 30, 30));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(15, 15, 15, 15);
        gbc.fill = GridBagConstraints.BOTH;
        gbc.weightx = 1;
        gbc.weighty = 1;
        
        addMenuButton(content, gbc, 0, 0, "➕  Add New User", e -> showAddUserDialog());
        addMenuButton(content, gbc, 1, 0, "👥  Manage Users", e -> showManageUsersDialog());
        addMenuButton(content, gbc, 0, 1, "💰  Update Tariff", e -> showUpdateTariffDialog());
        addMenuButton(content, gbc, 1, 1, "💳  Payment Status", e -> showPaymentStatusDialog());
        addMenuButton(content, gbc, 0, 2, "📋  All Payments", e -> showAllPaymentsDialog());
        addMenuButton(content, gbc, 1, 2, "🚪  Logout", e -> logout());
        
        panel.add(content, BorderLayout.CENTER);
        return panel;
    }
    
    private void addMenuButton(JPanel content, GridBagConstraints gbc, int x, int y, String text, ActionListener action) {
        JButton btn = new JButton("<html><center>" + text.replace("  ", "<br>") + "</center></html>");
        btn.setFont(headerFont);
        btn.setBackground(white);
        btn.setForeground(secondaryColor);
        btn.setFocusPainted(false);
        btn.setBorder(BorderFactory.createCompoundBorder(
            new LineBorder(new Color(200, 200, 200), 1, true),
            new EmptyBorder(20, 25, 20, 25)));
        btn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        btn.setPreferredSize(new Dimension(200, 80));
        
        btn.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseEntered(MouseEvent e) {
                btn.setBackground(primaryColor);
                btn.setForeground(white);
                btn.setBorder(BorderFactory.createCompoundBorder(
                    new LineBorder(primaryColor, 2),
                    new EmptyBorder(18, 23, 18, 23)));
            }
            @Override
            public void mouseExited(MouseEvent e) {
                btn.setBackground(white);
                btn.setForeground(secondaryColor);
                btn.setBorder(BorderFactory.createCompoundBorder(
                    new LineBorder(new Color(200, 200, 200), 1, true),
                    new EmptyBorder(20, 25, 20, 25)));
            }
        });
        
        btn.addActionListener(action);
        gbc.gridx = x;
        gbc.gridy = y;
        content.add(btn, gbc);
    }
    
    private void logout() {
        currentUser = null;
        currentRole = null;
        currentMeterNum = null;
        cardLayout.show(mainPanel, "LOGIN");
    }
    
    private JTextField createValidatedTextField(int cols, String validationType) {
        JTextField field = createStyledTextField(cols);
        
        switch (validationType) {
            case "letters":
                ((AbstractDocument) field.getDocument()).setDocumentFilter(new DocumentFilter() {
                    @Override
                    public void insertString(FilterBypass fb, int offset, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[a-zA-Z\\s]+")) super.insertString(fb, offset, string, attr);
                    }
                    @Override
                    public void replace(FilterBypass fb, int offset, int length, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[a-zA-Z\\s]+")) super.replace(fb, offset, length, string, attr);
                    }
                }); break;
            case "numbers":
                ((AbstractDocument) field.getDocument()).setDocumentFilter(new DocumentFilter() {
                    @Override
                    public void insertString(FilterBypass fb, int offset, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[0-9]+")) super.insertString(fb, offset, string, attr);
                    }
                    @Override
                    public void replace(FilterBypass fb, int offset, int length, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[0-9]+")) super.replace(fb, offset, length, string, attr);
                    }
                }); break;
            case "alphanumeric":
                ((AbstractDocument) field.getDocument()).setDocumentFilter(new DocumentFilter() {
                    @Override
                    public void insertString(FilterBypass fb, int offset, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[a-zA-Z0-9]+")) super.insertString(fb, offset, string, attr);
                    }
                    @Override
                    public void replace(FilterBypass fb, int offset, int length, String string, AttributeSet attr) throws BadLocationException {
                        if (string.matches("[a-zA-Z0-9]+")) super.replace(fb, offset, length, string, attr);
                    }
                }); break;
            default:
                break;
        }
        
        return field;
    }
    
    private boolean validateLettersOnly(String text, String fieldName) {
        if (!Pattern.matches("[a-zA-Z\\s]+", text)) {
            JOptionPane.showMessageDialog(this, fieldName + " must contain only letters!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            return false;
        }
        return true;
    }
    
    private boolean validateNumbersOnly(String text, String fieldName) {
        if (!Pattern.matches("[0-9]+", text)) {
            JOptionPane.showMessageDialog(this, fieldName + " must contain only numbers!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            return false;
        }
        return true;
    }
    
    private boolean validateAlphanumeric(String text, String fieldName) {
        if (!Pattern.matches("[a-zA-Z0-9]+", text)) {
            JOptionPane.showMessageDialog(this, fieldName + " must contain only letters and numbers!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            return false;
        }
        return true;
    }
    
    private void showAddUserDialog() {
        JDialog dialog = new JDialog(this, "Add New User", true);
        dialog.setSize(500, 550);
        dialog.setLocationRelativeTo(this);
        dialog.setResizable(false);
        
        JPanel mainPanel = new JPanel(new BorderLayout(10, 10));
        mainPanel.setBorder(new EmptyBorder(20, 20, 20, 20));
        mainPanel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Add New User");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        mainPanel.add(titleLabel, BorderLayout.NORTH);
        
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(white);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(8, 8, 8, 8);
        gbc.fill = GridBagConstraints.HORIZONTAL;
        
        JTextField fullNameField = createValidatedTextField(18, "letters");
        JTextField meterNumField = createValidatedTextField(18, "numbers");
        JComboBox<String> countryCombo = new JComboBox<>(new String[]{"Ethiopia", "Eritrea", "Djibouti", "Somalia", "Kenya", "Sudan"});
        JTextField regionField = createValidatedTextField(18, "letters");
        JTextField zoneField = createValidatedTextField(18, "letters");
        JTextField woredaField = createValidatedTextField(18, "letters");
        JTextField kebeleField = createValidatedTextField(18, "alphanumeric");
        
        addFormRow(formPanel, gbc, 0, "Full Name *", fullNameField);
        addFormRow(formPanel, gbc, 1, "Meter Number *", meterNumField);
        addFormRow(formPanel, gbc, 2, "Country *", countryCombo);
        addFormRow(formPanel, gbc, 3, "Region *", regionField);
        addFormRow(formPanel, gbc, 4, "Zone *", zoneField);
        addFormRow(formPanel, gbc, 5, "Woreda (Ketema) *", woredaField);
        addFormRow(formPanel, gbc, 6, "Kebele *", kebeleField);
        
        JPanel btnPanel = new JPanel(new FlowLayout(FlowLayout.CENTER, 15, 10));
        btnPanel.setBackground(white);
        
        JButton saveBtn = createStyledButton("SAVE USER", successColor, white);
        JButton cancelBtn = createStyledButton("CANCEL", dangerColor, white);
        
        saveBtn.addActionListener(e -> {
            String fullName = fullNameField.getText().trim();
            String meterNum = meterNumField.getText().trim();
            String region = regionField.getText().trim();
            String zone = zoneField.getText().trim();
            String woreda = woredaField.getText().trim();
            String kebele = kebeleField.getText().trim();
            
            if (fullName.isEmpty() || meterNum.isEmpty() || region.isEmpty() || zone.isEmpty() || woreda.isEmpty() || kebele.isEmpty()) {
                JOptionPane.showMessageDialog(dialog, "Please fill all required fields!", "Validation Error", JOptionPane.WARNING_MESSAGE);
                return;
            }
            
            if (!validateLettersOnly(fullName, "Full Name")) return;
            if (!validateNumbersOnly(meterNum, "Meter Number")) return;
            if (!validateLettersOnly(region, "Region")) return;
            if (!validateLettersOnly(zone, "Zone")) return;
            if (!validateLettersOnly(woreda, "Woreda")) return;
            if (!validateAlphanumeric(kebele, "Kebele")) return;
            
            try {
                String username = generateUsername(fullName);
                String password = generatePassword();
                
                PreparedStatement ps = conn.prepareStatement(
                    "INSERT INTO users (full_name, meter_num, country, region, zone, woreda, kebele, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'user')");
                ps.setString(1, fullName);
                ps.setString(2, meterNum);
                ps.setString(3, (String) countryCombo.getSelectedItem());
                ps.setString(4, region);
                ps.setString(5, zone);
                ps.setString(6, woreda);
                ps.setString(7, kebele);
                ps.setString(8, username);
                ps.setString(9, password);
                ps.executeUpdate();
                
                String info = "✓ User added successfully!\n\n━━━━━━━━━━━━━━━━━━━━━━\nUsername: " + username + "\nPassword: " + password + "\n━━━━━━━━━━━━━━━━━━━━━━";
                JOptionPane.showMessageDialog(dialog, info, "Success", JOptionPane.INFORMATION_MESSAGE);
                dialog.dispose();
            } catch (SQLException ex) {
                if (ex.getErrorCode() == 1062) {
                    JOptionPane.showMessageDialog(dialog, "Meter number or username already exists!", "Error", JOptionPane.ERROR_MESSAGE);
                } else {
                    JOptionPane.showMessageDialog(dialog, "Database Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        });
        
        cancelBtn.addActionListener(e -> dialog.dispose());
        
        btnPanel.add(saveBtn);
        btnPanel.add(cancelBtn);
        
        mainPanel.add(formPanel, BorderLayout.CENTER);
        mainPanel.add(btnPanel, BorderLayout.SOUTH);
        
        dialog.add(mainPanel);
        dialog.setVisible(true);
    }
    
    private void addFormRow(JPanel panel, GridBagConstraints gbc, int row, String labelText, JComponent field) {
        gbc.gridx = 0; gbc.gridy = row; gbc.gridwidth = 1;
        JLabel label = new JLabel(labelText);
        label.setFont(normalFont);
        panel.add(label, gbc);
        gbc.gridx = 1; gbc.gridwidth = 2;
        panel.add(field, gbc);
    }
    
    private String generateUsername(String fullName) {
        String[] parts = fullName.trim().split("\\s+");
        String username = parts[0].toLowerCase().replaceAll("[^a-z]", "");
        Random rand = new Random();
        username += rand.nextInt(10000);
        return username;
    }
    
    private String generatePassword() {
        String chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        Random rand = new Random();
        String password = "";
        for (int i = 0; i < 8; i++) {
            password += chars.charAt(rand.nextInt(chars.length()));
        }
        return password;
    }
    
    private void showManageUsersDialog() {
        JDialog dialog = new JDialog(this, "Manage Users", true);
        dialog.setSize(950, 500);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Manage Registered Users");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        String[] columns = {"ID", "Full Name", "Meter Num", "Country", "Region", "Zone", "Woreda", "Kebele", "Username", "Status"};
        DefaultTableModel model = new DefaultTableModel(columns, 0) {
            @Override
            public boolean isCellEditable(int row, int column) { return false; }
        };
        JTable table = new JTable(model);
        table.setRowHeight(26);
        table.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        table.getTableHeader().setBackground(primaryColor);
        table.getTableHeader().setForeground(white);
        table.getTableHeader().setFont(new Font("Segoe UI", Font.BOLD, 12));
        
        loadUserTable(model);
        
        JScrollPane scrollPane = new JScrollPane(table);
        panel.add(scrollPane, BorderLayout.CENTER);
        
        JPanel btnPanel = new JPanel(new FlowLayout(FlowLayout.CENTER, 12, 12));
        btnPanel.setBackground(white);
        
        JButton editBtn = createStyledButton("Edit", primaryColor, white);
        JButton deleteBtn = createStyledButton("Delete", dangerColor, white);
        JButton resetBtn = createStyledButton("Reset Password", accentColor, secondaryColor);
        JButton refreshBtn = createStyledButton("Refresh", secondaryColor, white);
        
        editBtn.addActionListener(e -> {
            int row = table.getSelectedRow();
            if (row >= 0) {
                String meterNum = (String) model.getValueAt(row, 2);
                showEditUserDialog(meterNum, model);
            } else {
                JOptionPane.showMessageDialog(dialog, "Please select a user!", "Warning", JOptionPane.WARNING_MESSAGE);
            }
        });
        
        deleteBtn.addActionListener(e -> {
            int row = table.getSelectedRow();
            if (row >= 0) {
                int confirm = JOptionPane.showConfirmDialog(dialog, "Are you sure you want to delete this user?", "Confirm Delete", JOptionPane.YES_NO_OPTION);
                if (confirm == JOptionPane.YES_OPTION) {
                    String meterNum = (String) model.getValueAt(row, 2);
                    try {
                        PreparedStatement ps = conn.prepareStatement("DELETE FROM users WHERE meter_num=?");
                        ps.setString(1, meterNum);
                        ps.executeUpdate();
                        model.removeRow(row);
                        JOptionPane.showMessageDialog(dialog, "User deleted successfully!", "Success", JOptionPane.INFORMATION_MESSAGE);
                    } catch (SQLException ex) {
                        JOptionPane.showMessageDialog(dialog, "Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                }
            }
        });
        
        resetBtn.addActionListener(e -> {
            int row = table.getSelectedRow();
            if (row >= 0) {
                String username = (String) model.getValueAt(row, 8);
                String newPass = generatePassword();
                try {
                    PreparedStatement ps = conn.prepareStatement("UPDATE users SET password=? WHERE username=?");
                    ps.setString(1, newPass);
                    ps.setString(2, username);
                    ps.executeUpdate();
                    JOptionPane.showMessageDialog(dialog, "New Password: " + newPass, "Password Reset", JOptionPane.INFORMATION_MESSAGE);
                } catch (SQLException ex) {
                    JOptionPane.showMessageDialog(dialog, "Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        });
        
        refreshBtn.addActionListener(e -> loadUserTable(model));
        
        btnPanel.add(editBtn);
        btnPanel.add(deleteBtn);
        btnPanel.add(resetBtn);
        btnPanel.add(refreshBtn);
        panel.add(btnPanel, BorderLayout.SOUTH);
        
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private void loadUserTable(DefaultTableModel model) {
        model.setRowCount(0);
        try {
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT * FROM users WHERE role='user' ORDER BY id");
            while (rs.next()) {
                model.addRow(new Object[]{
                    rs.getInt("id"), rs.getString("full_name"), rs.getString("meter_num"),
                    rs.getString("country"), rs.getString("region"), rs.getString("zone"),
                    rs.getString("woreda"), rs.getString("kebele"), rs.getString("username"), rs.getString("status")
                });
            }
        } catch (SQLException e) {
        }
    }
    
    private void showEditUserDialog(String meterNum, DefaultTableModel model) {
        JDialog dialog = new JDialog(this, "Edit User", true);
        dialog.setSize(450, 450);
        dialog.setLocationRelativeTo(this);
        
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBorder(new EmptyBorder(25, 25, 25, 25));
        formPanel.setBackground(white);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(8, 8, 8, 8);
        gbc.fill = GridBagConstraints.HORIZONTAL;
        
        try {
            PreparedStatement ps = conn.prepareStatement("SELECT * FROM users WHERE meter_num=?");
            ps.setString(1, meterNum);
            ResultSet rs = ps.executeQuery();
            
            if (rs.next()) {
                JTextField fullNameField = createValidatedTextField(16, "letters");
                JTextField regionField = createValidatedTextField(16, "letters");
                JTextField zoneField = createValidatedTextField(16, "letters");
                JTextField woredaField = createValidatedTextField(16, "letters");
                JTextField kebeleField = createValidatedTextField(16, "alphanumeric");
                JComboBox<String> statusCombo = new JComboBox<>(new String[]{"active", "inactive"});
                
                fullNameField.setText(rs.getString("full_name"));
                regionField.setText(rs.getString("region"));
                zoneField.setText(rs.getString("zone"));
                woredaField.setText(rs.getString("woreda"));
                kebeleField.setText(rs.getString("kebele"));
                statusCombo.setSelectedItem(rs.getString("status"));
                
                String[] labels = {"Full Name:", "Region:", "Zone:", "Woreda:", "Kebele:", "Status:"};
                JComponent[] fields = {fullNameField, regionField, zoneField, woredaField, kebeleField, statusCombo};
                
                for (int i = 0; i < labels.length; i++) {
                    gbc.gridx = 0; gbc.gridy = i; gbc.gridwidth = 1;
                    formPanel.add(new JLabel(labels[i]), gbc);
                    gbc.gridx = 1; gbc.gridwidth = 2;
                    formPanel.add(fields[i], gbc);
                }
                
                JButton saveBtn = createStyledButton("UPDATE", successColor, white);
                saveBtn.addActionListener(e -> {
                    try {
                        PreparedStatement psUpdate = conn.prepareStatement(
                            "UPDATE users SET full_name=?, region=?, zone=?, woreda=?, kebele=?, status=? WHERE meter_num=?");
                        psUpdate.setString(1, fullNameField.getText().trim());
                        psUpdate.setString(2, regionField.getText().trim());
                        psUpdate.setString(3, zoneField.getText().trim());
                        psUpdate.setString(4, woredaField.getText().trim());
                        psUpdate.setString(5, kebeleField.getText().trim());
                        psUpdate.setString(6, (String) statusCombo.getSelectedItem());
                        psUpdate.setString(7, meterNum);
                        psUpdate.executeUpdate();
                        
                        loadUserTable(model);
                        JOptionPane.showMessageDialog(dialog, "User updated successfully!", "Success", JOptionPane.INFORMATION_MESSAGE);
                        dialog.dispose();
                    } catch (SQLException ex) {
                        JOptionPane.showMessageDialog(dialog, "Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                });
                
                gbc.gridx = 1; gbc.gridy = labels.length;
                gbc.gridwidth = 2;
                gbc.insets = new Insets(20, 8, 8, 8);
                formPanel.add(saveBtn, gbc);
            }
        } catch (SQLException e) {
        }
        
        dialog.add(formPanel);
        dialog.setVisible(true);
    }
    
    private void showUpdateTariffDialog() {
        JDialog dialog = new JDialog(this, "Update Tariff Rates", true);
        dialog.setSize(600, 400);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Electricity Tariff Rates");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        String[] columns = {"ID", "Min KWh", "Max KWh", "Price/KWh (ETB)", "Effective Date"};
        DefaultTableModel model = new DefaultTableModel(columns, 0);
        JTable table = new JTable(model);
        
        loadTariffTable(model);
        
        JScrollPane scrollPane = new JScrollPane(table);
        panel.add(scrollPane, BorderLayout.CENTER);
        
        JPanel formPanel = new JPanel(new FlowLayout(FlowLayout.CENTER, 15, 12));
        formPanel.setBackground(white);
        
        JTextField minField = createValidatedTextField(8, "numbers");
        JTextField maxField = createValidatedTextField(8, "numbers");
        JTextField priceField = new JTextField(8);
        
        formPanel.add(new JLabel("Min KWh:"));
        formPanel.add(minField);
        formPanel.add(new JLabel("Max KWh (0=unlimited):"));
        formPanel.add(maxField);
        formPanel.add(new JLabel("Price:"));
        formPanel.add(priceField);
        
        JButton addBtn = createStyledButton("Add Tariff", successColor, white);
        addBtn.addActionListener(e -> {
            try {
                int min = Integer.parseInt(minField.getText());
                int max = Integer.parseInt(maxField.getText());
                double price = Double.parseDouble(priceField.getText());
                
                if (price <= 0) {
                    JOptionPane.showMessageDialog(dialog, "Price must be greater than 0!", "Validation Error", JOptionPane.WARNING_MESSAGE);
                    return;
                }
                
                PreparedStatement ps = conn.prepareStatement(
                    "INSERT INTO tariff (min_kwh, max_kwh, price_per_kwh, effective_date) VALUES (?, ?, ?, CURDATE())");
                ps.setInt(1, min);
                if (max == 0) ps.setNull(2, Types.INTEGER);
                else ps.setInt(2, max);
                ps.setDouble(3, price);
                ps.executeUpdate();
                
                loadTariffTable(model);
                minField.setText(""); maxField.setText(""); priceField.setText("");
                JOptionPane.showMessageDialog(dialog, "Tariff added successfully!", "Success", JOptionPane.INFORMATION_MESSAGE);
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(dialog, "Please enter valid numbers!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            } catch (SQLException ex) {
                JOptionPane.showMessageDialog(dialog, "Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
            }
        });
        
        formPanel.add(addBtn);
        panel.add(formPanel, BorderLayout.SOUTH);
        
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private void loadTariffTable(DefaultTableModel model) {
        model.setRowCount(0);
        try {
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT * FROM tariff ORDER BY min_kwh");
            while (rs.next()) {
                String maxKwh = rs.getObject("max_kwh") != null ? String.valueOf(rs.getInt("max_kwh")) : "Unlimited";
                model.addRow(new Object[]{
                    rs.getInt("id"), rs.getInt("min_kwh"), maxKwh,
                    String.format("%.2f", rs.getDouble("price_per_kwh")), rs.getDate("effective_date")
                });
            }
        } catch (SQLException e) {
        }
    }
    
    private void showPaymentStatusDialog() {
        JDialog dialog = new JDialog(this, "Payment Status", true);
        dialog.setSize(800, 450);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("User Payment Status");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        String[] columns = {"Meter Num", "Full Name", "Total Paid (ETB)", "Payment Count", "Last Payment"};
        DefaultTableModel model = new DefaultTableModel(columns, 0);
        JTable table = new JTable(model);
        
        try {
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery(
                "SELECT u.meter_num, u.full_name, COALESCE(SUM(p.total_amount), 0) as total_paid, COUNT(p.id) as pay_count, MAX(p.payment_date) as last_payment " +
                "FROM users u LEFT JOIN payments p ON u.meter_num = p.meter_num WHERE u.role = 'user' GROUP BY u.meter_num, u.full_name ORDER BY u.full_name");
            while (rs.next()) {
                String lastPay = rs.getTimestamp("last_payment") != null ? rs.getTimestamp("last_payment").toString().substring(0, 16) : "No payment";
                model.addRow(new Object[]{
                    rs.getString("meter_num"), rs.getString("full_name"),
                    String.format("%.2f", rs.getDouble("total_paid")),
                    rs.getInt("pay_count"), lastPay
                });
            }
        } catch (SQLException e) {
        }
        
        panel.add(new JScrollPane(table), BorderLayout.CENTER);
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private void showAllPaymentsDialog() {
        JDialog dialog = new JDialog(this, "All Payments", true);
        dialog.setSize(1000, 500);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("All Payment Records");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        String[] columns = {"ID", "Meter", "Name", "Usage", "Amount", "VAT", "Total", "Month", "Method", "Date"};
        DefaultTableModel model = new DefaultTableModel(columns, 0);
        JTable table = new JTable(model);
        
        try {
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT * FROM payments ORDER BY payment_date DESC");
            while (rs.next()) {
                model.addRow(new Object[]{
                    rs.getInt("id"), rs.getString("meter_num"), rs.getString("full_name"),
                    rs.getInt("usage_kwh"), "ETB " + String.format("%.2f", rs.getDouble("amount")),
                    "ETB " + String.format("%.2f", rs.getDouble("vat")), "ETB " + String.format("%.2f", rs.getDouble("total_amount")),
                    rs.getString("payment_month"), rs.getString("payment_method"),
                    rs.getTimestamp("payment_date").toString().substring(0, 16)
                });
            }
        } catch (SQLException e) {
        }
        
        panel.add(new JScrollPane(table), BorderLayout.CENTER);
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private JPanel createUserPanel() {
        JPanel panel = new JPanel(new BorderLayout());
        panel.setBackground(lightBg);
        
        JPanel header = createHeader("User Dashboard", successColor);
        panel.add(header, BorderLayout.NORTH);
        
        JPanel content = new JPanel(new GridBagLayout());
        content.setBackground(lightBg);
        content.setBorder(new EmptyBorder(30, 30, 30, 30));
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(15, 15, 15, 15);
        gbc.fill = GridBagConstraints.BOTH;
        gbc.weightx = 1;
        gbc.weighty = 1;
        
        addMenuButton(content, gbc, 0, 0, "⚡  Calculate Bill", e -> showCalculateDialog());
        addMenuButton(content, gbc, 1, 0, "💳  Pay Bill", e -> showPayBillDialog());
        addMenuButton(content, gbc, 0, 1, "📜  Payment History", e -> showPaymentHistoryDialog());
        addMenuButton(content, gbc, 1, 1, "🧾  View Receipts", e -> showReceiptsDialog());
        addMenuButton(content, gbc, 0, 2, "🚪  Logout", e -> logout());
        
        panel.add(content, BorderLayout.CENTER);
        return panel;
    }
    
    private void showCalculateDialog() {
        JDialog dialog = new JDialog(this, "Calculate Bill", true);
        dialog.setSize(430, 360);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new GridBagLayout());
        panel.setBorder(new EmptyBorder(25, 25, 25, 25));
        panel.setBackground(white);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);
        gbc.fill = GridBagConstraints.HORIZONTAL;
        
        JTextField prevField = createValidatedTextField(12, "numbers");
        JTextField currField = createValidatedTextField(12, "numbers");
        
        gbc.gridx = 0; gbc.gridy = 0; gbc.gridwidth = 1;
        panel.add(new JLabel("Previous Reading (KWh):"), gbc);
        gbc.gridx = 1; gbc.gridwidth = 2;
        panel.add(prevField, gbc);
        
        gbc.gridx = 0; gbc.gridy = 1; gbc.gridwidth = 1;
        panel.add(new JLabel("Current Reading (KWh):"), gbc);
        gbc.gridx = 1; gbc.gridwidth = 2;
        panel.add(currField, gbc);
        
        JTextArea resultArea = new JTextArea(6, 32);
        resultArea.setFont(new Font("Monospaced", Font.PLAIN, 13));
        resultArea.setEditable(false);
        resultArea.setBackground(new Color(250, 250, 250));
        resultArea.setBorder(BorderFactory.createLineBorder(new Color(200, 200, 200)));
        
        JButton calcBtn = createStyledButton("CALCULATE", primaryColor, white);
        calcBtn.addActionListener(e -> {
            try {
                int prev = Integer.parseInt(prevField.getText().trim());
                int curr = Integer.parseInt(currField.getText().trim());
                
                if (curr < prev) {
                    JOptionPane.showMessageDialog(dialog, "Current reading must be >= previous reading!", "Validation Error", JOptionPane.WARNING_MESSAGE);
                    return;
                }
                
                int usage = curr - prev;
                double rate = getTariffRate(usage);
                double amount = usage * rate;
                double vat = amount * 0.15;
                double total = amount + vat;
                
                String result = String.format(
                    "  ═══════════════════════════════════\n" +
                    "  Usage: %d KWh\n  Rate: ETB %.2f / KWh\n\n" +
                    "  Sub Total: ETB %.2f\n" +
                    "  VAT (15%%): ETB %.2f\n" +
                    "  ────────────────────────────────\n" +
                    "  TOTAL: ETB %.2f\n" +
                    "  ═══════════════════════════════════",
                    usage, rate, amount, vat, total);
                resultArea.setText(result);
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(dialog, "Enter valid numbers!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            }
        });
        
        gbc.gridx = 0; gbc.gridy = 2; gbc.gridwidth = 3;
        panel.add(calcBtn, gbc);
        
        gbc.gridy = 3;
        panel.add(resultArea, gbc);
        
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private double getTariffRate(int kwh) {
        try {
            PreparedStatement ps = conn.prepareStatement(
                "SELECT price_per_kwh FROM tariff WHERE min_kwh <= ? AND (max_kwh IS NULL OR max_kwh >= ?) ORDER BY min_kwh DESC LIMIT 1");
            ps.setInt(1, kwh);
            ps.setInt(2, kwh);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) return rs.getDouble("price_per_kwh");
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return 0.50;
    }
    
    private void showPayBillDialog() {
        JDialog dialog = new JDialog(this, "Pay Bill", true);
        dialog.setSize(520, 580);
        dialog.setLocationRelativeTo(this);
        
        JPanel mainPanel = new JPanel(new BorderLayout(15, 15));
        mainPanel.setBorder(new EmptyBorder(20, 20, 20, 20));
        mainPanel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Pay Electricity Bill");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        mainPanel.add(titleLabel, BorderLayout.NORTH);
        
        JPanel formPanel = new JPanel(new GridBagLayout());
        formPanel.setBackground(white);
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(8, 8, 8, 8);
        gbc.fill = GridBagConstraints.HORIZONTAL;
        
        JTextField meterField = new JTextField(currentMeterNum, 18);
        meterField.setEditable(false);
        meterField.setFont(normalFont);
        meterField.setBackground(new Color(240, 240, 240));
        
        JTextField prevField = createValidatedTextField(18, "numbers");
        JTextField currField = createValidatedTextField(18, "numbers");
        
        String[] ethiopianMonths = {"Meskerem", "Tikimt", "Hidar", "Tahsas", "Tir", "Yekatit", "Megabit", "Miyazia", "Ginbot", "Sene", "Hamle", "Nehase"};
        JComboBox<String> monthCombo = new JComboBox<>(ethiopianMonths);
        
        String[] banks = {"Commercial Bank of Ethiopia", "Dashen Bank", "Awash Bank", "Bank of Abyssinia", 
                         "United Bank", "Nib Bank", "Cooperative Bank", "Oromia Bank"};
        JComboBox<String> bankCombo = new JComboBox<>(banks);
        
        addFormRow(formPanel, gbc, 0, "Meter Number:", meterField);
        addFormRow(formPanel, gbc, 1, "Previous Reading:", prevField);
        addFormRow(formPanel, gbc, 2, "Current Reading:", currField);
        addFormRow(formPanel, gbc, 3, "Payment Month:", monthCombo);
        addFormRow(formPanel, gbc, 4, "Payment Method:", bankCombo);
        
        JLabel totalLabel = new JLabel("  Total: ETB 0.00");
        totalLabel.setFont(new Font("Segoe UI", Font.BOLD, 18));
        totalLabel.setForeground(successColor);
        
        JButton calcBtn = createStyledButton("CALCULATE", primaryColor, white);
        final double[] totalAmount = {0};
        
        calcBtn.addActionListener(e -> {
            try {
                int prev = Integer.parseInt(prevField.getText().trim());
                int curr = Integer.parseInt(currField.getText().trim());
                
                if (curr < prev) {
                    JOptionPane.showMessageDialog(dialog, "Current reading must be >= previous!", "Validation Error", JOptionPane.WARNING_MESSAGE);
                    return;
                }
                
                int usage = curr - prev;
                double rate = getTariffRate(usage);
                double amount = usage * rate;
                double vat = amount * 0.15;
                totalAmount[0] = amount + vat;
                
                totalLabel.setText("  Total: ETB " + String.format("%.2f", totalAmount[0]));
            } catch (NumberFormatException ex) {
                JOptionPane.showMessageDialog(dialog, "Enter valid numbers!", "Validation Error", JOptionPane.WARNING_MESSAGE);
            }
        });
        
        gbc.gridx = 0; gbc.gridy = 5; gbc.gridwidth = 3;
        formPanel.add(calcBtn, gbc);
        
        gbc.gridy = 6;
        formPanel.add(totalLabel, gbc);
        
        JButton payBtn = createStyledButton("PAY NOW", successColor, white);
        payBtn.addActionListener(e -> {
            if (totalAmount[0] == 0) {
                JOptionPane.showMessageDialog(dialog, "Please calculate the bill first!", "Warning", JOptionPane.WARNING_MESSAGE);
                return;
            }
            try {
                int prev = Integer.parseInt(prevField.getText().trim());
                int curr = Integer.parseInt(currField.getText().trim());
                int usage = curr - prev;
                double rate = getTariffRate(usage);
                double amount = usage * rate;
                double vat = amount * 0.15;
                double total = amount + vat;
                
                PreparedStatement ps = conn.prepareStatement(
                    "INSERT INTO payments (meter_num, full_name, previous_reading, current_reading, usage_kwh, tariff_rate, amount, vat, total_amount, payment_month, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                ps.setString(1, currentMeterNum);
                ps.setString(2, currentUser);
                ps.setInt(3, prev);
                ps.setInt(4, curr);
                ps.setInt(5, usage);
                ps.setDouble(6, rate);
                ps.setDouble(7, amount);
                ps.setDouble(8, vat);
                ps.setDouble(9, total);
                ps.setString(10, (String) monthCombo.getSelectedItem());
                ps.setString(11, (String) bankCombo.getSelectedItem());
                ps.executeUpdate();
                
                generateReceipt(prev, curr, usage, rate, amount, vat, total, 
                    (String) monthCombo.getSelectedItem(), (String) bankCombo.getSelectedItem());
                
                JOptionPane.showMessageDialog(dialog, "✓ Payment Successful!", "Success", JOptionPane.INFORMATION_MESSAGE);
                dialog.dispose();
            } catch (HeadlessException | NumberFormatException | SQLException ex) {
                JOptionPane.showMessageDialog(dialog, "Error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
            }
        });
        
        gbc.gridy = 7;
        formPanel.add(payBtn, gbc);
        
        mainPanel.add(formPanel, BorderLayout.CENTER);
        dialog.add(mainPanel);
        dialog.setVisible(true);
    }
    
    private void generateReceipt(int prev, int curr, int usage, double rate, double amount, double vat, double total, String month, String method) {
        LocalDateTime now = LocalDateTime.now();
        DateTimeFormatter dateFormatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
        
        StringBuilder receipt = new StringBuilder();
        receipt.append("╔═══════════════════════════════════════════╗\n");
        receipt.append("║      ELECTRICITY BILL RECEIPT              ║\n");
        receipt.append("║    FEDERAL DEMOCRATIC REPUBLIC OF ETHIOPIA ║\n");
        receipt.append("╠═══════════════════════════════════════════╣\n");
        receipt.append(String.format("║ Customer Name: %-30s║\n", currentUser));
        receipt.append(String.format("║ Meter Number: %-30s║\n", currentMeterNum));
        receipt.append(String.format("║ Payment Date: %-28s║\n", now.format(dateFormatter)));
        receipt.append(String.format("║ Payment Month: %-27s║\n", month));
        receipt.append("╠═══════════════════════════════════════════╣\n");
        receipt.append(String.format("║ Previous Reading: %-23d KWh║\n", prev));
        receipt.append(String.format("║ Current Reading: %-24d KWh║\n", curr));
        receipt.append(String.format("║ Usage: %-34d KWh║\n", usage));
        receipt.append(String.format("║ Tariff Rate: ETB %-25.2f║\n", rate));
        receipt.append("╠═══════════════════════════════════════════╣\n");
        receipt.append(String.format("║ Sub Total: ETB %-27.2f║\n", amount));
        receipt.append(String.format("║ VAT (15%%): ETB %-28.2f║\n", vat));
        receipt.append("║ ────────────────────────────────────────────║\n");
        receipt.append(String.format("║ TOTAL AMOUNT: ETB %-22.2f║\n", total));
        receipt.append("╠═══════════════════════════════════════════╣\n");
        receipt.append(String.format("║ Payment Method: %-24s║\n", method));
        receipt.append("║ Status: PAID ✓                             ║\n");
        receipt.append("╚═══════════════════════════════════════════╝");
        
        try {
            String fileName = "receipt_" + currentMeterNum + "_" + now.format(DateTimeFormatter.ofPattern("yyyyMMddHHmmss")) + ".txt";
            try (PrintWriter writer = new PrintWriter(new FileWriter(fileName))) {
                writer.print(receipt.toString());
            }
            JOptionPane.showMessageDialog(null, receipt.toString() + "\n\nReceipt saved to: " + fileName, "Payment Receipt", JOptionPane.INFORMATION_MESSAGE);
        } catch (HeadlessException | IOException e) {
            JOptionPane.showMessageDialog(null, receipt.toString(), "Payment Receipt", JOptionPane.INFORMATION_MESSAGE);
        }
    }
    
    private void showPaymentHistoryDialog() {
        JDialog dialog = new JDialog(this, "Payment History", true);
        dialog.setSize(800, 450);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Your Payment History");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        String[] columns = {"Date", "Usage(KWh)", "Amount (ETB)", "VAT (ETB)", "Total (ETB)", "Month", "Method"};
        DefaultTableModel model = new DefaultTableModel(columns, 0);
        JTable table = new JTable(model);
        
        try {
            PreparedStatement ps = conn.prepareStatement("SELECT * FROM payments WHERE meter_num=? ORDER BY payment_date DESC");
            ps.setString(1, currentMeterNum);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                model.addRow(new Object[]{
                    rs.getTimestamp("payment_date").toString().substring(0, 16),
                    rs.getInt("usage_kwh"), String.format("%.2f", rs.getDouble("amount")),
                    String.format("%.2f", rs.getDouble("vat")), String.format("%.2f", rs.getDouble("total_amount")),
                    rs.getString("payment_month"), rs.getString("payment_method")
                });
            }
        } catch (SQLException e) {
        }
        
        panel.add(new JScrollPane(table), BorderLayout.CENTER);
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private void showReceiptsDialog() {
        JDialog dialog = new JDialog(this, "View Last Receipt", true);
        dialog.setSize(520, 400);
        dialog.setLocationRelativeTo(this);
        
        JPanel panel = new JPanel(new BorderLayout(15, 15));
        panel.setBorder(new EmptyBorder(15, 15, 15, 15));
        panel.setBackground(white);
        
        JLabel titleLabel = new JLabel("Last Payment Receipt");
        titleLabel.setFont(titleFont);
        titleLabel.setForeground(primaryColor);
        panel.add(titleLabel, BorderLayout.NORTH);
        
        JTextArea receiptArea = new JTextArea();
        receiptArea.setFont(new Font("Monospaced", Font.PLAIN, 11));
        receiptArea.setEditable(false);
        
        try {
            PreparedStatement ps = conn.prepareStatement("SELECT * FROM payments WHERE meter_num=? ORDER BY payment_date DESC LIMIT 1");
            ps.setString(1, currentMeterNum);
            ResultSet rs = ps.executeQuery();
            
            if (rs.next()) {
                LocalDateTime date = rs.getTimestamp("payment_date").toLocalDateTime();
                String receipt = String.format(
                    "╔════════════════════════════════════════╗\n" +
                    "║        LAST RECEIPT                      ║\n" +
                    "╠════════════════════════════════════════╣\n" +
                    "║ Name: %-30s    ║\n" +
                    "║ Meter: %-30s    ║\n" +
                    "║ Date: %-30s    ║\n" +
                    "╠════════════════════════════════════════╣\n" +
                    "║ Prev: %d | Curr: %d | Usage: %d KWh      ║\n" +
                    "║ Rate: ETB %.2f                          ║\n" +
                    "╠════════════════════════════════════════╣\n" +
                    "║ Amount: ETB %-23.2f║\n" +
                    "║ VAT: ETB %-28.2f║\n" +
                    "║ TOTAL: ETB %-25.2f║\n" +
                    "╠════════════════════════════════════════╣\n" +
                    "║ Month: %-30s    ║\n" +
                    "║ Method: %-28s    ║\n" +
                    "║ Status: PAID ✓                         ║\n" +
                    "╚════════════════════════════════════════╝",
                    rs.getString("full_name"), rs.getString("meter_num"),
                    date.format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm")),
                    rs.getInt("previous_reading"), rs.getInt("current_reading"), rs.getInt("usage_kwh"),
                    rs.getDouble("tariff_rate"), rs.getDouble("amount"), rs.getDouble("vat"),
                    rs.getDouble("total_amount"), rs.getString("payment_month"), rs.getString("payment_method")
                );
                receiptArea.setText(receipt);
            } else {
                receiptArea.setText("No payments found!");
            }
        } catch (SQLException e) {
        }
        
        panel.add(new JScrollPane(receiptArea), BorderLayout.CENTER);
        dialog.add(panel);
        dialog.setVisible(true);
    }
    
    private JPanel createHeader(String title, Color bgColor) {
        JPanel header = new JPanel(new BorderLayout());
        header.setBackground(bgColor);
        header.setBorder(new EmptyBorder(15, 25, 15, 25));
        
        JLabel titleLabel = new JLabel(title + " - " + (currentUser != null ? currentUser : ""));
        titleLabel.setFont(headerFont);
        titleLabel.setForeground(white);

        JLabel roleLabel = new JLabel("👤 " + (currentRole != null ? currentRole.toUpperCase() : ""));
        roleLabel.setFont(normalFont);
        roleLabel.setForeground(accentColor);
        
        header.add(titleLabel, BorderLayout.WEST);
        header.add(roleLabel, BorderLayout.EAST);
        String user = null;
        System.out.println("user = " + user);
        String headerPanel = null;
System.out.println("headerPanel = " + headerPanel);
        return header;
    }
    
    private JButton createStyledButton(String text, Color bg, Color fg) {
        JButton btn = new JButton(text);
        btn.setBackground(bg);
        btn.setForeground(fg);
        btn.setFont(new Font("Segoe UI", Font.BOLD, 13));
        btn.setFocusPainted(false);
        btn.setBorder(new EmptyBorder(12, 22, 12, 22));
        btn.setCursor(new Cursor(Cursor.HAND_CURSOR));
        return btn;
    }
    
    public static void main(String[] args) {
        try {
            UIManager.setLookAndFeel("javax.swing.plaf.nimbus.NimbusLookAndFeel");
        } catch (ClassNotFoundException | IllegalAccessException | InstantiationException | UnsupportedLookAndFeelException e) {
            try {
                UIManager.setLookAndFeel("javax.swing.plaf.metal.MetalLookAndFeel");
            } catch (ClassNotFoundException | IllegalAccessException | InstantiationException | UnsupportedLookAndFeelException ex) {}
        }
        
        SwingUtilities.invokeLater(() -> {
            ElectricityBillingSystem app = new ElectricityBillingSystem();
            app.setVisible(true);
        });
    }
}