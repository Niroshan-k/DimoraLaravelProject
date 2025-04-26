public void removeByName(String name) {
        if (isEmpty()) {
            System.err.println("No patients found.");
            return;
        }
 
       
        if (head.patient.name.equalsIgnoreCase(name)) {
            System.out.println("Removing patient: " + head.patient.name);
            head = head.next;
            return;
        }
 
        Node current = head;
        while (current.next != null && !current.next.patient.name.equalsIgnoreCase(name)) {
            current = current.next;
        }
 
        if (current.next == null) {
            System.err.println("Patient named '" + name + "' not found in the queue.");
        } else {
            System.out.println("Removing patient: " + current.next.patient.name);
            current.next = current.next.next;
        }
    }